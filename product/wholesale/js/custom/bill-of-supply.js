$( document ).ready(function() {
    
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
                    $('#customer_id').val(ui.item.id);
                    $('#salesman_id').val(ui.item.salesman_id);
                    $('#rate_id').val(ui.item.rate_id);
                    $('#customer_name').val(ui.item.name); 
                    $('#statecode').val(ui.item.state);
                    $('#customer_city').val(ui.item.city).trigger('change');
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
          calculateAmount();
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
                        $('#errormsg').empty();
                        this.value = (ui.item ? ui.item.name : '');
                        $(this).closest('tr').find('.product_id').val(ui.item.id);
                        $(this).closest('tr').find('.mrp').val(ui.item.mrp);
                        $(this).closest('tr').find('.mfg').val(ui.item.mfg_company);
                        $(this).closest('tr').find('.batch').val(ui.item.batch);
                        $(this).closest('tr').find('.expiry').val(ui.item.expiry);
    
                        $(this).closest('tr').find('.ptr').val(ui.item.ptr);
                        $('#ptr-discount-model').find('#rate').val((typeof ui.item.rate !== 'undefined' && ui.item.rate !== '') ? ui.item.rate : 0);
                        $('#ptr-discount-model').find('#discount').val((typeof ui.item.discount !== 'undefined' && ui.item.discount !== '') ? ui.item.discount : 0);
                        $('#ptr-discount-model').find('#tr-id').val(tr);
                        $(this).closest('tr').find('.current_qty').val(ui.item.total_qty);
                        $(this).closest('tr').find('.qty_ratio').val(ui.item.ratio);
                        
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
                            rate_id : $('#rate_id').val(),
                            gst_id : 1,
                            action: "searchProductWithoutExpiredAndWithoutZeroStock"
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
        
        // ON CHANGE BILL TYPE TO CHANGE INVOICE NUMBER
        $(".bill_type").change(function(){
            var bill_type = $("input[name='bill_type']:checked").val();
            $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'bill_type':bill_type, 'action':'getInvoiceNoForBillOfSupply'},
                dataType: "json",
                success: function (data) {
                  $('#invoice_no').val(data.result);
                },
                error: function () {
                  $('#invoice_no').val(null);
                }
              });
        });
    
        // COUNT PRODUCT QTY RATE BY AMOUNT
        $('body').on('change keyup past', '.qty ', function () {
            var qty = $(this).val();
            var rate = $(this).closest('tr').find('.rate').val();
          
            qty = (qty != '' && !isNaN(qty)) ? parseFloat(qty) : 0;
            rate = (rate != '' && !isNaN(rate)) ? parseFloat(rate) : 0;
    
            var amount = (qty * rate);
            amount = (amount != '') ? parseFloat(amount) : 0;
            $(this).closest('tr').find('.totalamount').val(parseFloat(amount).toFixed(2));
    
            calculateAmount();
        });
        $('body').on('change keyup past', '.rate', function () {
            $(this).closest('tr').find('.qty').trigger("change");
        });
        
        
        $('body').on('change keyup past', '#discount_amount', function () {
            calculateAmount();
        });
        $('body').on('change', '#discount_type', function () {
            calculateAmount();
        });
        function calculateAmount(){
            var product_totalamount = 0;
            
            // COUNT TOTAL AMOUNT FOR PRODUCT
            $('.totalamount').each(function() {
                var val1 = $.trim($(this).val());
                console.log('val1 => '+val1);
                product_totalamount += (typeof val1 !== 'undefined' && !isNaN(val1) && val1 !== '') ? parseFloat(val1) : 0;
            });
            
            var totalamount = parseFloat(product_totalamount);
            
            var dis_value = $.trim($("#discount_amount").val());
            dis_value = (typeof dis_value !== 'undefined' && !isNaN(dis_value) && dis_value !== '') ? parseFloat(dis_value) : 0;
            if(dis_value > 0){
                var dis_type = $('#discount_type').val();
                if(dis_type == 'RS'){
                    totalamount -= dis_value;
                    $('#overalldiscount').val(dis_value.toFixed(2));
                }else{
                    var dis_amount = (totalamount*dis_value/100);
                    totalamount -= dis_amount;
                    $('#overalldiscount').val(dis_amount.toFixed(2));
                }
            }
            
            $('#total_amount').val(totalamount.toFixed(2));
            
            var round_amount = (Math.round(totalamount)-totalamount);
            
            $('#roundoff_amount').val((round_amount).toFixed(2));

            $('#final_amount').val(Math.round(totalamount).toFixed(2));
        }
        
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
                    showSuccessToast(data.message);
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
                 //  $('#addcustomer-errormsg').html(htmlerror);
                  $("html, body").animate({ scrollTop: 0 }, "slow");
    
                  $('#btn-addcustomer').html('Save');
                  $('#btn-addcustomer').prop('disabled', false);
                }
            });
    
        });
});