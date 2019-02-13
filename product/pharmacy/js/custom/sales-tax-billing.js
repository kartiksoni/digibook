$( document ).ready(function() {
    
    
    setTimeout(function(){
        calculate();
    }, 2000);

    /*---------------CUSTOMER CITY SELECT 2 AUTO SEARCH- START---------------*/
    $("#customer_city").select2({
         ajax: { 
           url: "ajax_second.php",
           type: "post",
           dataType: 'json',
           delay: 250,
           data: function (params) {
            return {
              searchTerm: params.term, // search term
              action: 'getCity'
            };
           },
           processResults: function (response) {
             return {
                results: response
             };
           },
           cache: true
        },
        placeholder: 'Search City'
    });
    $('#customer_city').on("select2:select", function(e) { 
        var val = $(this).val();
        setStateCode(val);
    });
  /*---------------CUSTOMER CITY SELECT 2 AUTO SEARCH- END---------------*/

    function setStateCode(id = null){
        $.ajax({
            type: "POST",
            url: 'ajax_second.php',
            data: {'id':id, 'action':'getStateByCityId'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                $('#statecode').val(data.result);
              }else{
                $('#statecode').val(null);
              }
            },
            error: function () {
              $('#statecode').val(null);
            }
        });
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
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addcustomer').html('Save');
              $('#btn-addcustomer').prop('disabled', false);
            },
            error: function () {
                showDangerToast('Somthing Want Wrong!');
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
      calculate();
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
        var qtyArray = getQty();
        
        $(this).mcautocomplete({
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
                var customer_name = $('#customer_name').val();
                if(customer_name !== ''){
                  console.log(ui.item);
                    $('#errormsg').empty();
                    this.value = (ui.item ? ui.item.name : '');
                    $(this).closest('tr').find('.product_id').val(ui.item.id);
                    $(this).closest('tr').find('.mrp').val(ui.item.mrp);
                    $(this).closest('tr').find('.mfg').val(ui.item.mfg_company);
                    $(this).closest('tr').find('.batch').val(ui.item.batch);
                    $(this).closest('tr').find('.expiry').val(ui.item.expiry);
                    // $(this).closest('tr').find('.ptr').val(ui.item.ptr);
                    $(this).closest('tr').find('.discount').val(ui.item.discount_per);
                    $(this).closest('tr').find('.rate').val(ui.item.rate);
                    $(this).closest('tr').find('.current_qty').val(ui.item.total_qty);
                    
    
                    //$(this).closest('tr').find('.qty').val(ui.item.total_qty);
                    $(this).closest('tr').find('.qty_ratio').val(ui.item.ratio);
    
                    var igst = (typeof ui.item.igst != 'undefined' && ui.item.igst != '' && !isNaN(ui.item.igst)) ? ui.item.igst : 0;
                    var cgst = (typeof ui.item.cgst != 'undefined' && ui.item.cgst != '' && !isNaN(ui.item.cgst)) ? ui.item.cgst : 0;
                    var sgst = (typeof ui.item.sgst != 'undefined' && ui.item.sgst != '' && !isNaN(ui.item.sgst)) ? ui.item.sgst : 0;
    
                    if(statecode == cur_statecode){
                      $(this).closest('tr').find('.igst').val(0);
                      $(this).closest('tr').find('.cgst').val(cgst);
                      $(this).closest('tr').find('.sgst').val(sgst);
                      $(this).closest('tr').find('.gst').val(parseFloat(cgst)+parseFloat(sgst));
                    }else{
                      $(this).closest('tr').find('.c_igst').val(igst);
                      $(this).closest('tr').find('.c_cgst').val(0);
                      $(this).closest('tr').find('.c_sgst').val(0);
                      $(this).closest('tr').find('.gst').val(parseFloat(igst));
                    }
                }else{
                    showDangerToast('Please Select Customer!');
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
                        action: "searchProductWithoutExpiredAndWithoutZeroStock"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                        if(data.status == true){
                            console.log(data);
                            $($this).closest('tr').find('.producterror').empty();
                            
                            var dataArray = new Array();

                            $.each(data.result, function(key, value){
                                var total_qty = (typeof value.total_qty !== 'undefined' && value.total_qty != '' && !isNaN(value.total_qty)) ? parseFloat(value.total_qty) : 0;
                                if(typeof qtyArray[value.id] !== 'undefined'){
                                  var qty = (!isNaN(qtyArray[value.id]) && qtyArray[value.id] != '') ? parseFloat(qtyArray[value.id]) : 0;
                                  value.total_qty = (total_qty-qty);
                                }
                                if(value.total_qty > 0){
                                  dataArray.push(value);
                                }
                            });

                            if(dataArray.length > 0){
                              response(dataArray)
                            }else{
                              $($this).closest('tr').find('.producterror').text("No results found");
                            }
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
    
    function getQty(){
        var data = new Array();
        $('#item-tbody').find(".product_id").each(function() {
          var product_id = $(this).closest('tr').find('.product_id').val();
          var qty = $(this).closest('tr').find('.qty').val();
          var freeqty = $(this).closest('tr').find('.freeqty').val();
          if(typeof product_id !== 'undefined' && product_id != ''){
              qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
              freeqty = (typeof freeqty !== 'undefined' && !isNaN(freeqty) && freeqty != '') ? parseFloat(freeqty) : 0;
              if(typeof data[product_id] !== 'undefined'){
                data[product_id] += (qty+freeqty);
              }else{
                data[product_id] = (qty+freeqty);
              }
          }
        });
        return data;
    }


    /*-----------------------------------JS FOR PRODUCT ITEMS COUNT CHARGES START-----------------------------------*/
    $('body').on('change keyup past', '.qty, .discount, .gst', function () {
        var qty = $(this).closest('tr').find('.qty').val();
        var rate = $(this).closest('tr').find('.rate').val();
        var gst = $(this).closest('tr').find('.gst').val();
        var igst = $(this).closest('tr').find('.igst').val();
        var cgst = $(this).closest('tr').find('.cgst').val();
        var sgst = $(this).closest('tr').find('.sgst').val();
        var discount = $(this).closest('tr').find('.discount').val();
        var statecode = $('#statecode').val();
        var cur_statecode = $('#cur_statecode').val();

        discount = (typeof discount !== 'undefined' && !isNaN(discount) && discount != '') ? parseFloat(discount) : 0;
        qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
        rate = (typeof rate !== 'undefined' && !isNaN(rate) && rate != '') ? parseFloat(rate) : 0;
      
        gst = (typeof gst !== 'undefined' && !isNaN(gst) && gst != '') ? parseFloat(gst) : 0;
        igst = (typeof igst !== 'undefined' && !isNaN(igst) && igst != '') ? parseFloat(igst) : 0;
        cgst = (typeof cgst !== 'undefined' && !isNaN(cgst) && cgst != '') ? parseFloat(cgst) : 0;
        sgst = (typeof sgst !== 'undefined' && !isNaN(sgst) && sgst != '') ? parseFloat(sgst) : 0;

        var amount = (qty*rate);
        var discount_amount = ((amount)-(amount*discount/100));
      
        $(this).closest('tr').find('.totalamount').val(discount_amount.toFixed(2));
      
        var total_igst = 0;
        var total_cgst = 0;
        var total_sgst = 0;
        var taxable_amount = ((discount_amount*100)/(100+gst)).toFixed(2);
      
        if(statecode == cur_statecode){
            total_cgst = (taxable_amount*cgst/100).toFixed(2);
            total_sgst = (taxable_amount*sgst/100).toFixed(2);
        }else{
            total_igst = (taxable_amount*igst/100).toFixed(2);
        }
      
        var tax = (parseFloat(total_igst)+parseFloat(total_cgst)+parseFloat(total_sgst));
        
        $(this).closest('tr').find('.gst_tax').val(parseFloat(tax).toFixed(2));
        calculate();
    });

    function calculate(){
      var statecode = $('#statecode').val();
      var cur_statecode = $('#cur_statecode').val();
      var totalamount = 0;
      
      var totalGstTax = 0;

      $('.totalamount').each(function() {
            var amount = $(this).val();
            amount = (typeof amount !== 'undefined' && !isNaN(amount) && amount != '') ? parseFloat(amount) : 0;
            totalamount += amount;
        
            var gst_tax = $(this).closest('tr').find('.gst_tax').val();
            totalGstTax += (typeof gst_tax !== 'undefined' && !isNaN(gst_tax) && gst_tax != '') ? parseFloat(gst_tax) : 0;
      });
      $('#alltotalamount').val(totalamount.toFixed(2));
      
        //set total gst
        if(statecode == cur_statecode){
            $('#totalcgst').val((totalGstTax/2).toFixed(2));
            $('#totalsgst').val((totalGstTax/2).toFixed(2));
            $('#totaligst').val(0);
        }else{
            $('#totalcgst').val(0);
            $('#totalsgst').val(0);
            $('#totaligst').val((totalGstTax).toFixed(2));
        }

      // get overall discount
      var discount = $('#discount_rs').val();
      discount = (typeof discount !== 'undefined' && !isNaN(discount) && discount != '') ? parseFloat(discount) : 0;
      totalamount -= discount;

      $('#sale_amount').val(totalamount.toFixed(2));

      // return value
      var return_val = $('#return_amount').val();
      return_val = (typeof return_val !== 'undefined' && !isNaN(return_val) && return_val != '') ? parseFloat(return_val) : 0;
      totalamount -= return_val;

      var round_amount = (Math.round(totalamount)-totalamount);
      $('#roundoff_amount').val(round_amount.toFixed(2));
      $('#final_amount').val(Math.round(totalamount).toFixed(2));
    }

    $('body').on('change keyup past', '#discount_per', function () {
        var dis_per = $(this).val();
        dis_per = (typeof dis_per !== 'undefined' && !isNaN(dis_per) && dis_per != '') ? parseFloat(dis_per) : 0;
        
        var total_amount = $('#alltotalamount').val();
        total_amount = (typeof total_amount !== 'undefined' && !isNaN(total_amount) && total_amount != '') ? parseFloat(total_amount) : 0;

        var per_amount = (total_amount*dis_per/100);
        per_amount = (typeof per_amount !== 'undefined' && !isNaN(per_amount) && per_amount != '') ? parseFloat(per_amount) : 0;
        
        $('#discount_rs').val(per_amount.toFixed(2));
        
        calculate();
        
    });
    
    $('body').on('change keyup past', '#discount_rs', function () {
        calculate();
    });
    /*-----------------------------------JS FOR PRODUCT ITEMS COUNT CHARGES END-------------------------------------*/
    
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
                showSuccessToast(data.message);
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
                showDangerToast(data.message);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#btn-addmissorder').html('Add Order');
                $('#btn-addmissorder').prop('disabled', false);
              }
            },
            error: function () {
                showDangerToast('Somthing Want Wrong!');
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
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
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
                 showSuccessToast(data.message);
            // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
           // $('#missedorder-errormsg').html(htmlsuccess);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#purchase-addproductmodel').modal('toggle');
            
            $('#add-product')[0].reset();

            $('#mis_product_id').val(data.result);
            $('#mis_product').val(dataarr.product_name);
            $('#mis_unit').val(dataarr.unit);
            
            $('#btn-addmissorder-tmp').prop('disabled', false);
          }else{
                showDangerToast(data.message);
            // htmlerror =  htmlerror.replace("##MSG##", data.message);
            // $('#addproduct-errormsg').html(htmlerror);
            $("html, body").animate({ scrollTop: 0 }, "slow");
          }
          $('#btn-addproduct').html('Save');
          $('#btn-addproduct').prop('disabled', false);
        },
        error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
          // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
          // $('#addproduct-errormsg').html(htmlerror);
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
      var qtyArray = getQty();
      
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
                    var current_stock = (typeof item.total_qty !== 'undefined' && !isNaN(item.total_qty) && item.total_qty != '') ? parseFloat(item.total_qty) : 0;
                    var already_added_stock = (typeof qtyArray[item.id] !== 'undefined' && !isNaN(qtyArray[item.id]) && qtyArray[item.id] != '') ? parseFloat(qtyArray[item.id]) : 0; 
                    current_stock -= already_added_stock;
                    
                    var finalhtml = html;
                    finalhtml = finalhtml.replace(/##PRODUCTID##/g, (typeof item.id !== 'undefined') ? item.id : '');
                    finalhtml = finalhtml.replace(/##PRODUCTNAME##/g, (typeof item.product_name !== 'undefined') ? item.product_name : '');
                    finalhtml = finalhtml.replace(/##MFG##/g, (typeof item.mfg_company !== 'undefined') ? item.mfg_company : '');
                    finalhtml = finalhtml.replace(/##GENERIC##/g, (typeof item.generic_name !== 'undefined') ? item.generic_name : '');
                    finalhtml = finalhtml.replace(/##EXPDATE##/g, (typeof item.ex_date !== 'undefined') ? item.ex_date : '');
                    finalhtml = finalhtml.replace(/##BATCH##/g, (typeof item.batch_no !== 'undefined') ? item.batch_no : '');
                    finalhtml = finalhtml.replace(/##MRP##/g, (typeof item.mrp !== 'undefined') ? item.mrp : '');
                    finalhtml = finalhtml.replace(/##RATIO##/g, (typeof item.ratio !== 'undefined') ? item.ratio : '');
                    finalhtml = finalhtml.replace(/##STOCK##/g, current_stock);
                    finalhtml = finalhtml.replace(/##RATE##/g, (typeof item.rate !== 'undefined') ? item.rate : '0');
                    finalhtml = finalhtml.replace(/##DISCOUNT##/g, (typeof item.discount !== 'undefined') ? item.discount : '0');
                    finalhtml = finalhtml.replace(/##GST##/g, (typeof item.igst !== 'undefined') ? item.igst : '');
                    finalhtml = finalhtml.replace(/##IGST##/g, (typeof item.igst !== 'undefined') ? item.igst : '');
                    finalhtml = finalhtml.replace(/##CGST##/g, (typeof item.cgst !== 'undefined') ? item.cgst : '');
                    finalhtml = finalhtml.replace(/##SGST##/g, (typeof item.sgst !== 'undefined') ? item.sgst : '');
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

       var data = [];
        $("input:checkbox[class=alt_product_id]:checked").each(function (){
            var tmparray = [];
            tmparray['product_id'] = $(this).val();
            tmparray['product_name'] = $(this).closest('tr').find('.alt_product_name').html();
            tmparray['mfg'] = $(this).closest('tr').find('.alt_product_mfg').html();
            tmparray['expiry'] = $(this).closest('tr').find('.alt_product_expiry').html();
            tmparray['batch'] = $(this).closest('tr').find('.alt_product_batch').html();
            tmparray['mrp'] = $(this).closest('tr').find('.alt_product_mrp').html();
            tmparray['ratio'] = $(this).closest('tr').find('.alt_product_ratio').val();
            tmparray['rate'] = $(this).closest('tr').find('.alt_product_rate').val();
            tmparray['stock'] = $(this).closest('tr').find('.alt_product_stock').html();
            tmparray['discount'] = $(this).closest('tr').find('.alt_product_discount').html();
            tmparray['igst'] = $(this).closest('tr').find('.alt_product_igst').val();
            tmparray['cgst'] = $(this).closest('tr').find('.alt_product_cgst').val();
            tmparray['sgst'] = $(this).closest('tr').find('.alt_product_sgst').val();
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
              $('#item-tbody tr:last').find('.mfg').val(item.mfg);
              $('#item-tbody tr:last').find('.batch').val(item.batch);
              $('#item-tbody tr:last').find('.expiry').val(item.expiry);
              $('#item-tbody tr:last').find('.mrp').val(item.mrp);
              $('#item-tbody tr:last').find('.qty_ratio').val(item.ratio);
              $('#item-tbody tr:last').find('.rate').val(item.rate);
              $('#item-tbody tr:last').find('.current_qty').val(item.stock);
              $('#item-tbody tr:last').find('.discount').val(item.discount);

              var igst = (typeof item.igst != 'undefined' && item.igst != '') ? parseFloat(item.igst) : 0;
              var cgst = (typeof item.cgst != 'undefined' && item.cgst != '') ? parseFloat(item.cgst) : 0;
              var sgst = (typeof item.sgst != 'undefined' && item.sgst != '') ? parseFloat(item.sgst) : 0;

              if(state_code == curstate_code){
                $('#item-tbody tr:last').find('.igst').val(0);
                $('#item-tbody tr:last').find('.cgst').val(cgst);
                $('#item-tbody tr:last').find('.sgst').val(sgst);

                $('#item-tbody tr:last').find('.gst').val(cgst+sgst);
              }else{
                $('#item-tbody tr:last').find('.igst').val(igst);
                $('#item-tbody tr:last').find('.cgst').val(0);
                $('#item-tbody tr:last').find('.sgst').val(0);

                $('#item-tbody tr:last').find('.gst').val(igst);
              }

              if(tr == '' && i == 0){
                $('#item-tbody tr:last').find('.btn-remove-item').hide();
              }
          });

        $('#alternate_product_model').modal('hide');

      }else{
        showDangerToast('Somthing Want Wrong! Try Again.');
        return false;
      }

    });

    /*-----------------------------------JS FOR ALTERNATE PRODUCT SEARCH END--------------------------------------------------*/


    /* -------------------------------------------------JS FOR SAVE & RETURN START----------------------------------------------------- */

    $( "#btn-saveandreturn" ).click(function() {
        var customer_id = $('#customer_id').val();
        var customer_name = $('#customer_name').val();
        var city_id = $('#customer_city').val();
        var city_name = $("#customer_city option:selected").text();

        if(customer_id != ''){
          setLastCreditNoteNo();
          $('#sale_return_form').find('#r_city_name').val(city_name);
          $('#sale_return_form').find('#r_city_id').val(city_id);
          $('#sale_return_form').find('#r_customer_id').val(customer_id);
          $('#sale_return_form').find('#r_customer_name').val(customer_name);
          $('#save_and_return_model').modal('show');
        }else{
          showInfoToast('Please Select Customer!');
        }
    });

    function setLastCreditNoteNo(){
      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'action':'getLastCreditNoteNo'},
          dataType: "json",
          success: function (data) {
            if(data.status == true){
              $('#sale_return_form').find('#credit_note_no').val(data.result);
            }else{
              $('#sale_return_form').find('#credit_note_no').val(null);
              showDangerToast('Credit note no. not found!');
            }
          },
          error: function () {
            $('#sale_return_form').find('#credit_note_no').val(null);
            showDangerToast('Credit note no. not found!');
          }
      });
    }

    $('body').on('keyup click', '.r_product', function () {
        var $this = $(this);
        $(this).mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
            showHeader: true,
            columns: [{
                name: 'Product Name',
                width: '200px;',
                valueField: 'product_name'
            }, {
                name: 'Batch',
                width: '200px',
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
                $($this).closest('tr').find('.r_product_id').val(ui.item.id);
                $($this).closest('tr').find('.r_tax_bill_id').val(ui.item.tax_billing_id);
                $($this).closest('tr').find('.r_mfg_co').val(ui.item.mfg_company);
                $($this).closest('tr').find('.r_batch').val(ui.item.batch_no);
                $($this).closest('tr').find('.r_expiry').val(ui.item.ex_date);
                $($this).closest('tr').find('.r_mrp').val(ui.item.mrp);
                $($this).closest('tr').find('.r_qty').val(ui.item.qty);
                $($this).closest('tr').find('.r_qty_ratio').val(ui.item.qty_ratio);
                $($this).closest('tr').find('.r_rate').val(ui.item.rate);
                $($this).closest('tr').find('.r_discount').val(ui.item.discount);
                $($this).closest('tr').find('.r_gst').val(ui.item.gst);
                $($this).closest('tr').find('.r_igst').val(ui.item.igst);
                $($this).closest('tr').find('.r_cgst').val(ui.item.cgst);
                $($this).closest('tr').find('.r_sgst').val(ui.item.sgst);
                $($this).closest('tr').find('.r_gst_tax').val(ui.item.gst_tax);
                $($this).closest('tr').find('.r_amount').val(ui.item.totalamount);

                

                $('#model-invoice-no').text((typeof ui.item.invoice_no !== 'undefined' && ui.item.invoice_no != '') ? ui.item.invoice_no : '-');
                $('#model-invoice-date').text((typeof ui.item.invoice_date !== 'undefined' && ui.item.invoice_date != '') ? ui.item.invoice_date : '-');
                $('#model-invoice-amount').text((typeof ui.item.invoice_amount !== 'undefined' && ui.item.invoice_amount != '' && !isNaN(ui.item.invoice_amount)) ? parseFloat(ui.item.invoice_amount).toFixed(2) : '0');
                $('#show-invoice-no-model').modal('show');

                 $(this).closest('tr').find('.r_qty').trigger('change');
                
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
                        customer_id : $('#r_customer_id').val(),
                        action: "getProductForCustomerReturn"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                      if(data.status == true){
                        $($this).closest('tr').find('.r_product_error').empty();
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
                        $($this).closest('tr').find('.r_product_error').text("No results found");
                      }

                    }
                });
            }
        });
    });

    $('#show-invoice-no-model').on('shown.bs.modal', function() {
      $('#btn-invoice-ok').focus();
    });

    // ADD MORE ITEM RETURN
    $('body').on('click', '.btn-add-more-item-return', function () {
      var trlength = $('#sale-return-body tr').length+1;
      var html = $('#hidden-sale-return').html();
      html = html.replace('<table>','');
      html = html.replace('</table>','');
      html = html.replace('<tbody>','');
      html = html.replace('</tbody>','');
      html = html.replace('##SRNO##',trlength);
      $('#sale-return-body').append(html);
    });

    // ADD MORE ITEM RETURN
    $('body').on('click', '.btn-remove-item-return', function () {
      $(this).closest ('tr').remove ();
    });

    $('body').on('change keyup past', '.r_qty, .r_discount, .r_gst', function () {
      var qty = $(this).closest('tr').find('.r_qty').val();
      var rate = $(this).closest('tr').find('.r_rate').val();
      var gst = $(this).closest('tr').find('.r_gst').val();
      var discount = $(this).closest('tr').find('.r_discount').val();

      discount = (typeof discount !== 'undefined' && !isNaN(discount) && discount != '') ? parseFloat(discount) : 0;
      qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
      rate = (typeof rate !== 'undefined' && !isNaN(rate) && rate != '') ? parseFloat(rate) : 0;
      gst = (typeof gst !== 'undefined' && !isNaN(gst) && gst != '') ? parseFloat(gst) : 0;

      var amount = (qty*rate);
      var discount_amount = ((amount)-(amount*discount/100));
      
      $(this).closest('tr').find('.r_amount').val(discount_amount.toFixed(2));
      var tax = (((discount_amount)-(discount_amount)*(gst/(100+gst))) * gst / 100);
      // var tax = (discount_amount*gst/(100+gst));
      $(this).closest('tr').find('.r_gst_tax').val(tax.toFixed(2));

      returnCalculation();
    });

    function returnCalculation(){
      var totalamount = 0;
      $('.r_amount').each(function() {
        var amount = $(this).val();
        amount = (typeof amount !== 'undefined' && !isNaN(amount) && amount != '') ? parseFloat(amount) : 0;
        totalamount += amount;
      });
      $('#r_finalamount').val(totalamount.toFixed(2));
    }
    
    $('#sale_return_form').on('submit',function(event){
        event.preventDefault();
        var data = $(this).serialize();
        $.ajax({
          type: "POST",
          url: 'ajax_second.php',
          data: {'action':'addSaleReturn', 'data': data},
          dataType: "json",
          beforeSend: function() {
            $('#btn-add-return').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
            $('#btn-add-return').prop('disabled', true);
          },
          success: function (data) {
            if(data.status == true){
              $('#return_amount').val((typeof data.result.total_amount !== 'undefined' && !isNaN(data.result.total_amount) && data.result.total_amount != '') ? data.result.total_amount : 0);
              $('#return_id').val((typeof data.result.id !== 'undefined') ? data.result.id : '');
              calculate();
              $('#sale_return_form').find('#credit_note_no').val(null);
              $('#sale_return_form').find('#r_city_name').val(null);
              $('#sale_return_form').find('#r_city_id').val(null);
              $('#sale_return_form').find('#r_customer_name').val(null);
              $('#sale_return_form').find('#r_customer_id').val(null);
              $('#sale_return_form').find('#r_remarks').val(null);
              $('#sale_return_form').find('#r_finalamount').val(null);
              $("#sale-return-body").find("tr:gt(0)").remove();
              $("#sale-return-body>tr:first").find('input').val(null);
              showSuccessToast(data.message);
              $('#save_and_return_model').modal('hide');
            }else{
              showDangerToast(data.message);
              return false;
            }
            $('#btn-add-return').html('Save');
            $('#btn-add-return').prop('disabled', false);
          },
          error: function () {
            showDangerToast('Somthing Want Wrong! Try Again');
            $('#btn-add-return').html('Save');
            $('#btn-add-return').prop('disabled', false);
            return false;
          }
        });
    })
    /* -------------------------------------------------JS FOR SAVE & RETURN START----------------------------------------------------- */
    
    /*--------------------------------------------------JS FOR APPLY TEMPLATE START-----------------------------------------------------*/

    
    $("#templateapply-form").on("submit", function(event){
      event.preventDefault();
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var data = $(this).serialize();
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
      var template_id = (typeof dataarr.template !== 'undefined' && dataarr.template !== '') ? dataarr.template : '';
      var customer_id = $('#customer_id').val();
        if(customer_id !== ''){
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
                    $('#item-tbody tr:last').find('.product').val(item.name);
                    $('#item-tbody tr:last').find('.product_id').val(item.id);
                    $('#item-tbody tr:last').find('.mfg').val(item.mfg_company);
                    $('#item-tbody tr:last').find('.batch').val(item.batch);
                    $('#item-tbody tr:last').find('.expiry').val(item.expiry);
                    $('#item-tbody tr:last').find('.mrp').val(item.mrp);
                    $('#item-tbody tr:last').find('.qty').val(item.qty);
                    $('#item-tbody tr:last').find('.qty_ratio').val(item.ratio);
                    $('#item-tbody tr:last').find('.rate').val(item.rate);
                    $('#item-tbody tr:last').find('.current_qty').val(item.total_qty);
                    $('#item-tbody tr:last').find('.discount').val(item.discount);
    
                    var igst = (typeof item.igst != 'undefined' && item.igst != '') ? parseFloat(item.igst) : 0;
                    var cgst = (typeof item.cgst != 'undefined' && item.cgst != '') ? parseFloat(item.cgst) : 0;
                    var sgst = (typeof item.sgst != 'undefined' && item.sgst != '') ? parseFloat(item.sgst) : 0;
    
                    if(state_code == curstate_code){
                      $('#item-tbody tr:last').find('.igst').val(0);
                      $('#item-tbody tr:last').find('.cgst').val(cgst);
                      $('#item-tbody tr:last').find('.sgst').val(sgst);
    
                      $('#item-tbody tr:last').find('.gst').val(cgst+sgst);
                    }else{
                      $('#item-tbody tr:last').find('.igst').val(igst);
                      $('#item-tbody tr:last').find('.cgst').val(0);
                      $('#item-tbody tr:last').find('.sgst').val(0);
    
                      $('#item-tbody tr:last').find('.gst').val(igst);
                    }
                    
                    $('#item-tbody tr:last').find('.qty').trigger("change");
                    if(tr == '' && i == 0){
                      $('#item-tbody tr:last').find('.btn-remove-item').hide();
                    }
                  });
                  $('#apply_template_model').modal('hide');
                }else{
                  showDangerToast(data.message);
                }
                $('#btn-addtemplate').html('Apply');
                $('#btn-addtemplate').prop('disabled', false);
              },
              error: function () {
                showDangerToast('Somthing Want Wrong!!');
                $('#btn-addtemplate').html('Apply');
                $('#btn-addtemplate').prop('disabled', false);
              }
            });
          }else{
            return false;  
          }
        }else{
            showDangerToast('Please select customer!');
            $("html, body").animate({ scrollTop: 0 }, "slow");
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
            showSuccessToast(data.message);
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
            showDangerToast(data.message);
          }
          $('#btn-adddoctor').html('Save');
          $('#btn-adddoctor').prop('disabled', false);
        },
        error: function () {
            showDangerToast('Somthing Want Wrong!');
            $('#btn-adddoctor').html('Save');
            $('#btn-adddoctor').prop('disabled', false);
        }
      });
    });
    
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
            $('#doctor_mobile').val('');
          }
        });
    });

    /*--------------------------------------------------JS FOR ADD DOCTOR END-----------------------------------------------------*/

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
        var cur_statecode = $('#cur_statecode').val();
        
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
                
                if(ui.item.state == cur_statecode){
                    $('#totaligst_tr').hide();
                }else{
                    $('#totalcgst_tr').hide();
                    $('#totalsgst_tr').hide();
                }
                
              if(ui.item.limit_total >= 0){
                $('#customer_id').val(ui.item.id);
                $('#customer_name').val(ui.item.name);
                $('#customer_title').val(ui.item.title);
                $('#bytempcust').val(ui.item.id);                  //for template
                $('#customer_mobile').val(ui.item.mobile);
                $('#customer_email').val(ui.item.email);
                $('#statecode').val(ui.item.state);
                $('#customer_u_id').val(ui.item.temp_customer);
                $('#limit_total').val(ui.item.limit_total);
                setCustomerCity(ui.item.city_id, ui.item.city_name);
                
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
                    $('#customer_title').val(ui.item.title);
                    $('#bytempcust').val(ui.item.id);                  //for template
                    $('#customer_mobile').val(ui.item.mobile);
                    $('#customer_email').val(ui.item.email);
                    $('#statecode').val(ui.item.state);
                    $('#customer_u_id').val(ui.item.temp_customer);
                    $('#limit_total').val(ui.item.limit_total);
                    setCustomerCity(ui.item.city_id, ui.item.city_name);
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
                    beforeSend: function() {
                        $($this).closest('.col-12').find('#customer_loader').show();
                    },
                    success: function (data) {
                        $('#customer_loader').hide();
                        if(data.status == true){
                            $('.customererror').empty();
                            response(data.result)
                        }else{
                            $('.customererror').empty();
                            $($this).closest('.col-12').find('.customererror').text("No results found");
                        }
                    },error: function () {
                        $('#customer_loader').hide();
                        $($this).closest('.col-12').find('.customererror').text("No results found");
                        // $('.customererror').text("No results found");
                    }
                });
            }
        });
    });
    
    function setCustomerCity(id = null, name = null){
        if((typeof id !== 'undefined' && id != '') && (typeof name !== 'undefined' && name != '')){
            console.log('id => '+id);
            console.log('name => '+name);
            $('#customer_city').append('<option selected="selected" value="'+id+'">'+name+'</option>');
        }else{
            $('#customer_city').val('').trigger('change');
        }
    }
    
    $('body').on('click', '#show_previous_bill_product', function() {
        var customer_id = $('#customer_id').val();
        if(customer_id != ''){
            ShowCustomerBill(customer_id);
        }else{
            showInfoToast('Please select customer!');
        }
    });
    
    /*----------GAUTAM MAKWANA | SHOW LAST 6 BILL OF CUSTOMER START----------------*/
    function ShowCustomerBill(customer_id = null){
      var qtyArray = getQty();
      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'customer_id':customer_id, 'action':'ShowCustomerBill'},
          dataType: "json",
          beforeSend: function() {
              $('#previous_bill_product_loader').show();
          },
          success: function (data) {
            if(data.status == true){
                var html = $('#hidden-lastbill').html();
                html = html.replace("<table>", "");
                html = html.replace("</table>", "");
                html = html.replace("<tbody>", "");
                html = html.replace("</tbody>", "");

                $('#lastbill-product-tbody').empty();
                if(data.result.length > 0){
                  $.each(data.result, function (key, value) {
                    var invoice_date = (typeof value.invoice_date !== 'undefined') ? value.invoice_date : '';
                    var invoice_no = (typeof value.invoice_no !== 'undefined') ? value.invoice_no : '';
                    var blankRow = '<tr><td colspan="12" class="text-center"><strong>Invoice Date : '+invoice_date+' &nbsp;|&nbsp; Invoice No : '+invoice_no+'</strong></td></tr>';
                    $('#lastbill-product-tbody').append(blankRow);

                    if(value.detail.length > 0){
                      $.each(value.detail, function (k, v) {
                          var currentstock = (typeof v.currentstock !== 'undefined' && v.currentstock != '' && !isNaN(v.currentstock)) ? parseFloat(v.currentstock) : 0;
                          if(typeof qtyArray[v.product_id] !== 'undefined'){
                            var existing_qty = (!isNaN(qtyArray[v.product_id]) && qtyArray[v.product_id] != '') ? parseFloat(qtyArray[v.product_id]) : 0;
                            v.currentstock = (currentstock-existing_qty);
                          }
                          
                          var finalhtml = html;
                          finalhtml = finalhtml.replace(/##SRNO##/g, key+1);
                          finalhtml = finalhtml.replace(/##PRODUCTNAME##/g, (typeof v.product_name != 'undefined') ?  v.product_name : '');
                          finalhtml = finalhtml.replace(/##PRODUCTID##/g, (typeof v.product_id != 'undefined') ?  v.product_id : '');
                          finalhtml = finalhtml.replace(/##MGF##/g, (typeof v.mfg_co != 'undefined') ?  v.mfg_co : '');
                          finalhtml = finalhtml.replace(/##BATCH##/g, (typeof v.batch != 'undefined') ?  v.batch : '');
                          finalhtml = finalhtml.replace(/##EXPIRY##/g, (typeof v.expiry != 'undefined') ?  v.expiry : '');
                          finalhtml = finalhtml.replace(/##MRP##/g, (typeof v.mrp != 'undefined' && v.mrp != '') ?  v.mrp : 0);
                          finalhtml = finalhtml.replace(/##QTY##/g, (typeof v.qty != 'undefined' && v.qty != '') ?  v.qty : 0);
                          finalhtml = finalhtml.replace(/##RATIO##/g, (typeof v.qty_ratio != 'undefined' && v.qty_ratio != '') ?  v.qty_ratio : 0);
                          finalhtml = finalhtml.replace(/##STOCK##/g, (typeof v.currentstock != 'undefined' && v.currentstock != '') ?  v.currentstock : 0);
                          finalhtml = finalhtml.replace(/##RATE##/g, (typeof v.rate != 'undefined' && v.rate != '') ?  v.rate : 0);
                          finalhtml = finalhtml.replace(/##DISCOUNT##/g, (typeof v.discount != 'undefined' && v.discount != '') ?  v.discount : 0);
                          finalhtml = finalhtml.replace(/##GST##/g, (typeof v.gst != 'undefined' && v.gst != '') ?  v.gst : 0);
                          finalhtml = finalhtml.replace(/##CGST##/g, (typeof v.cgst != 'undefined' && v.cgst != '') ?  v.cgst : 0);
                          finalhtml = finalhtml.replace(/##SGST##/g, (typeof v.sgst != 'undefined' && v.sgst != '') ?  v.sgst : 0);
                          finalhtml = finalhtml.replace(/##IGST##/g, (typeof v.igst != 'undefined' && v.igst != '') ?  v.igst : 0);
                          finalhtml = finalhtml.replace(/##GSTTAX##/g, (typeof v.gst_tax != 'undefined' && v.gst_tax != '') ?  v.gst_tax : 0);
                          finalhtml = finalhtml.replace(/##AMOUNT##/g, (typeof v.totalamount != 'undefined' && v.totalamount != '') ?  v.totalamount : 0);
                          finalhtml = finalhtml.replace(/##EXPIRED##/g, (typeof v.expired != 'undefined' && v.expired == 1) ?  'Expired!' : '');
                          $('#lastbill-product-tbody').append(finalhtml);
                      });
                    }

                  });
                }
                $('.lastbill-check-all').prop('checked', false);
                $('#lastbill_model').modal('show');
            }else{
                showDangerToast('Product not found!');
            }
            $('#previous_bill_product_loader').hide();
          },
          error: function () {
            $('#previous_bill_product_loader').hide();
            showDangerToast('Somthing want wrong!');
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
      var checked_color = '#ccc';
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
            showDangerToast('Some product stock is not available!');
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
          data.mfg = $(this).closest('tr').find('.mfg').html();
          data.batch = $(this).closest('tr').find('.batch').html();
          data.expiry = $(this).closest('tr').find('.expiry').html();
          data.mrp = $(this).closest('tr').find('.mrp').html();
          data.expired = $(this).closest('tr').find('.expired').html();
          data.qty = $(this).closest('tr').find('.qty').html();
          data.ratio = $(this).closest('tr').find('.ratio').val();
          data.stock = $(this).closest('tr').find('.stock').val();
          data.rate = $(this).closest('tr').find('.rate').val();
          data.discount = $(this).closest('tr').find('.discount').html();
          data.gst = $(this).closest('tr').find('.gst').html();
          data.igst = $(this).closest('tr').find('.igst').val();
          data.cgst = $(this).closest('tr').find('.cgst').val();
          data.sgst = $(this).closest('tr').find('.sgst').val();
          data.gst_tax = $(this).closest('tr').find('.gst_tax').html();
          data.totalamount = $(this).closest('tr').find('.amount').html();
          finaldata.push(data);
        });

        if(finaldata.length > 0){
          var html = $('#hiddenItemHtml').html();
          html = html.replace("<table>", "");
          html = html.replace("</table>", "");
          html = html.replace("<tbody>", "");
          html = html.replace("</tbody>", "");

          if($('#item-tbody tr:last').find('.product_id').val() == ''){
            $('#item-tbody').empty();
          }
          var currentLength = $('#item-tbody tr').length;
          currentLength = (typeof currentLength !== 'undefined' && !isNaN(currentLength) && currentLength != '') ? parseFloat(currentLength) : 0;

          $.each(finaldata, function (key, value) {
            var htmltmp = html;
            htmltmp = htmltmp.replace(/##SRNO##/g, key+(1+currentLength));
            $('#item-tbody').append(htmltmp);

            $('#item-tbody tr:last').find('.product').val(value.product);
            $('#item-tbody tr:last').find('.product_id').val(value.product_id);
            $('#item-tbody tr:last').find('.mfg').val(value.mfg);
            $('#item-tbody tr:last').find('.batch').val(value.batch);
            $('#item-tbody tr:last').find('.expiry').val(value.expiry);
            $('#item-tbody tr:last').find('.mrp').val(value.mrp);
            $('#item-tbody tr:last').find('.expired').html(value.expired);
            $('#item-tbody tr:last').find('.qty').val(value.qty);
            $('#item-tbody tr:last').find('.current_qty').val(value.stock);
            $('#item-tbody tr:last').find('.qty_ratio').val(value.ratio);
            $('#item-tbody tr:last').find('.rate').val(value.rate);
            $('#item-tbody tr:last').find('.discount').val(value.discount);
            $('#item-tbody tr:last').find('.gst').val(value.gst);
            $('#item-tbody tr:last').find('.c_igst').val(value.igst);
            $('#item-tbody tr:last').find('.c_cgst').val(value.cgst);
            $('#item-tbody tr:last').find('.c_sgst').val(value.sgst);
            $('#item-tbody tr:last').find('.gst_tax').val(value.gst_tax);
            $('#item-tbody tr:last').find('.totalamount').val(value.totalamount);
          });
        }
      $('#item-tbody tr:last').find('.qty').trigger("change");
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
            $(this).trigger("change");
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