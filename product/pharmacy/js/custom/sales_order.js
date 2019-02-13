$( document ).ready(function() {

  var tbl = $('.datatable').DataTable( {
        "ajax": "ajax.php?action=getSalesOrder",
        "columns": [
            { "data": "no" },
            { "data": "order_date" },
            { "data": "customer_name" },
            { "data": "totalorder" },
            { "data" : "id",
                "render": function (data)
                {
                    return '<button data-id='+data.customer_id+' data-group = '+data.group+' class="btn btn-success p-2 edit-permnent"><i class="icon-pencil mr-0"></i></button>';
                }

            }

        ],
        //"order": [[1, 'asc']]
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
            },/* {
                name: 'Generic Name',
                width: '250px;',
                valueField: 'generic_name'
            },*/ {
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
                this.value = (ui.item ? ui.item.name : '');
                //$('#results').text(ui.item ? 'Selected: ' + ui.item.name + ', ' + ui.item.purchase_id + ', ' + ui.item.batch : 'Nothing selected, input was ' + this.value);
                
                $('#product_id').val(ui.item.id);
                $('#mrp').val(ui.item.mrp);
                $("#btn-add-tmp").prop("disabled",false);

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
                        action: "searchProductWithExpired"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                        if(data.status == true){
                          $('.product-error').empty();
                          response(data.result);
                        }else{
                          $('.product-error').text("No results found");
                        }
                    }
                });
            }
        });
    });

    $(".reset").click(function(){
      var edit = $('#sales-order-form').find('#editid').val();
      if(edit !== ''){
        $('#'+edit).css("background-color", "#FFFFFF");
        $('#'+edit).find('.delete-temp').show();
      }

      $('#sales-order-form')[0].reset();
      $('#sales-order-form').find('input[type=hidden]').val(null);
      $("#btn-add-tmp").prop("disabled",true);
      $("#customer_id").val('').trigger('change');
  });

  $("#sales-order-form").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serializeArray();
      var serialdata = $(this).serialize();

      if(data.length){

        var post = [];
        post['customer_name'] = $("#customer_id option:selected").text();
        $.each(data, function (key, val) {
            post[val.name] = val.value;
        });
          // reset form data
          $('.reset').trigger("click");

        
          // set value to data
          var randomnumber = Math.floor((Math.random()*1000) + 1);
          var html = $('#addproduct-tr-html').html();
          html = html.replace("<table>", "");
          html = html.replace("</table>", "");
          html = html.replace("<tbody>", "");
          html = html.replace("</tbody>", "");

          if(typeof post.editid != 'undefined' && post.editid != ''){
            html = html.replace('<tr id="##DATAID##">', "");
            html = html.replace("</tr>", "");
          }else{
            html = html.replace("##DATAID##", 'tr-'+randomnumber);
          }
          html = html.replace(/##CUSTOMERNAME##/g, (typeof post.customer_name !== 'undefined') ? post.customer_name : '');
          html = html.replace("##CUSTOMERID##", (typeof post.customer_id !== 'undefined') ? post.customer_id : '');
          html = html.replace(/##PRODUCTNAME##/g, (typeof post.product !== 'undefined') ? post.product : '');
          html = html.replace("##PRODUCTID##", (typeof post.product_id !== 'undefined') ? post.product_id : '');
          html = html.replace(/##QTY##/g, (typeof post.qty !== 'undefined') ? post.qty : 0);
          html = html.replace(/##MRP##/g, (typeof post.mrp !== 'undefined') ? post.mrp : 0);
          html = html.replace(/##DISCOUNT##/g, (typeof post.discount !== 'undefined') ? post.discount : 0);
          html = html.replace(/##ID##/g, (typeof post.id !== 'undefined' && post.id !== '') ? post.id : '');
          
          var rowCount = $('#tbody-tmp tr').length;
          if(rowCount == 0){
            $('#tmpdata-div').show();
          }
          if(typeof post.editid != 'undefined' && post.editid != ''){
            $('#'+post.editid).html(html);
            $('#'+post.editid).css("background-color", "#FFFFFF");
          }else{
            $('#tbody-tmp').append(html);
          }
      }else{
        return false;
      }

  });

    function updateSalesOrder(data){
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    
      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'action':'updateSalesOrder', 'data': data},
          dataType: "json",
          beforeSend: function() {
            $('#btn-add-tmp').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
            $('#btn-add-tmp').prop('disabled', true);
          },
          success: function (data) {
            if(data.status == true){
                 showSuccessToast(data.message);
              // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
             // $('#errormsg').html(htmlsuccess);
              $("html, body").animate({ scrollTop: 0 }, "slow");
              $('#btn-add-tmp').html('Add');

              // reset form data
              $('.reset').trigger("click");
              $('.delete-permnent').show();
              tbl.ajax.reload();
            }else{
                showDangerToast(data.message);
              // htmlerror =  htmlerror.replace("##MSG##", data.message);
              // $('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");
              $('#btn-add-tmp').html('Update');
              $('#btn-add-tmp').prop('disabled', false);
            }
          },
          error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
            // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
            // $('#errormsg').html(htmlerror);
            $("html, body").animate({ scrollTop: 0 }, "slow");

            $('#btn-add-tmp').html('Update');
            $('#btn-add-tmp').prop('disabled', false);
          }
      });
      return false;
    }

    $('body').on('click', '.delete-temp', function () {
      var conf = confirm('Are you sure want to delete this record?');
      var id = $(this).attr('data-id');
      var $this = $(this);
      if(conf){
        if(id != ''){
            // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
            // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
            $.ajax({
              url: "ajax.php",
              data: {'id': id,'action': 'deleteSalesOrder'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                 showSuccessToast(data.message);
                  // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                  // $('#errormsg').html(htmlsuccess);
                  $("html, body").animate({ scrollTop: 0 }, "slow");
                  $($this).closest ('tr').remove();
                  if($('#tbody-tmp tr').length == 0){
                    console.log('rowCount => '+$('#tbody-tmp tr').length);
                    $('#tmpdata-div').hide();
                  }
                  tbl.ajax.reload();
                }else{
                    showDangerToast(data.message);
                    // htmlerror =  htmlerror.replace("##MSG##", data.message);
                    // $('#errormsg').html(htmlerror);
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }
              },
              error: function () {
                showDangerToast('Somthing Want Wrong! Try again.');
                // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
                // $('#errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
            });
            return false;
        }else{
          $($this).closest ('tr').remove();
          if($('#tbody-tmp tr').length == 0){
            $('#tmpdata-div').hide();
          }
          return false;
        }
      }else{
        return false;
      }
  });

  $('body').on('click', '.edit-temp', function () {

      var dataid = $(this).closest ('tr').attr('id');
      var customer_name = $(this).closest ('tr').find('.customer_name').val();
      var customer_id = $(this).closest ('tr').find('.customer_id').val();
      var product_name = $(this).closest ('tr').find('.product_name').val();
      var product_id = $(this).closest ('tr').find('.product_id').val();
      var qty = $(this).closest ('tr').find('.qty').val();
      var discount = $(this).closest ('tr').find('.discount').val();
      var mrp = $(this).closest ('tr').find('.mrp').val();
      var id = $(this).closest ('tr').find('.id').val();


      // set all value in form

      $('#sales-order-form').find('#editid').val(dataid);
      $('#sales-order-form').find('#customer_id').val(customer_id).trigger('change');
      $('#sales-order-form').find('#product').val(product_name);
      $('#sales-order-form').find('#product_id').val(product_id);
      $('#sales-order-form').find('#qty').val(qty);
      $('#sales-order-form').find('#discount').val(discount);
      $('#sales-order-form').find('#mrp').val(mrp);
      $('#sales-order-form').find('#id').val(id);

      var deletebtn = $(this).closest('tr').find('.delete-temp');
      $(deletebtn).hide();
      $('.delete-temp').not(deletebtn).show();

      $('#tbody-tmp tr').css("background-color", "#FFFFFF")
      $('#'+dataid).css("background-color", "#A9A9A9");
      $("html, body").animate({ scrollTop: 0 }, "slow");
      $('#btn-add-tmp').prop('disabled', false);
      return false;
    });


  $("#sales-order-final-form").on("submit", function(event){
      event.preventDefault();
        var data = $(this).serialize();
        // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'action':'addSalesOrder', 'data': data},
          dataType: "json",
          beforeSend: function() {
            $('.btn-saveproduct').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
            $('.btn-saveproduct').prop('disabled', true);
          },
          success: function (data) {
            if(data.status == true){
                 showSuccessToast(data.message);
             // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
             // $('#errormsg').html(htmlsuccess);
              $("html, body").animate({ scrollTop: 0 }, "slow");
              $('.btn-saveproduct').html('Save');
              $('.btn-saveproduct').prop('disabled', false);
              $('#tbody-tmp').empty();
              $('#sales-order-final-form').find('#day').val(null);
              $('#tmpdata-div').hide();
              tbl.ajax.reload();
            }else{
                showDangerToast(data.message);
             // htmlerror =  htmlerror.replace("##MSG##", data.message);
             // $('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");
              $('.btn-saveproduct').html('Save');
              $('.btn-saveproduct').prop('disabled', false);
            }
          },
          error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
           // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
           // $('#errormsg').html(htmlerror);
            $("html, body").animate({ scrollTop: 0 }, "slow");

            $('.btn-saveproduct').html('Save');
            $('.btn-saveproduct').prop('disabled', false);
          }
      });
    });


    $('body').on('click', '.delete-permnent', function () {
      var id = $(this).attr('data-id');
      var group = $(this).attr('data-group');
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var conf = confirm('Are you sure want to delete this record?');
      
      if(conf && id !== ''){
        $.ajax({
            url: "ajax.php",
            data: {'id': id, 'group': group,'action': 'deleteSalesOrder'},            
            dataType: "json",
            type: "POST",
            success: function (data) {
              if(data.status == true){
                 showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                tbl.ajax.reload();
              }else{
                showDangerToast(data.message);
                  // htmlerror =  htmlerror.replace("##MSG##", data.message);
                  // $('#errormsg').html(htmlerror);
                  $("html, body").animate({ scrollTop: 0 }, "slow");
              }
            },
            error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");
            }
        });
      }else{
        return false;
      }
        
  });

  $('body').on('click', '.edit-permnent', function () {
    var id = $(this).attr('data-id');
    var group = $(this).attr('data-group');
    var $this = $(this);

    if(id !== ''){
      $.ajax({
          url: "ajax.php",
          data: {'id': id, 'group':group, 'action': 'getEditDataForSalesOrder'},            
          dataType: "json",
          type: "POST",
          success: function (data) {
            if(data.status == true){

              $('#tmpdata-div').show();

              var html = $('#addproduct-tr-html').html();
              html = html.replace("<table>", "");
              html = html.replace("</table>", "");
              html = html.replace("<tbody>", "");
              html = html.replace("</tbody>", "");

              $('#tbody-tmp').empty();
              $.each(data.result.data, function (key, value) {
                var tmphtml = html;
                var randomnumber = Math.floor((Math.random()*1000) + 1);
                tmphtml = tmphtml.replace("##DATAID##", 'tr-'+randomnumber);
                tmphtml = tmphtml.replace(/##CUSTOMERNAME##/g, (typeof value.customer_name !== 'undefined') ? value.customer_name : '');
                tmphtml = tmphtml.replace("##CUSTOMERID##", (typeof value.customer_id !== 'undefined') ? value.customer_id : '');
                tmphtml = tmphtml.replace(/##PRODUCTNAME##/g, (typeof value.product_name !== 'undefined') ? value.product_name : '');
                tmphtml = tmphtml.replace("##PRODUCTID##", (typeof value.product_id !== 'undefined') ? value.product_id : '');
                tmphtml = tmphtml.replace(/##QTY##/g, (typeof value.qty !== 'undefined') ? value.qty : 0);
                tmphtml = tmphtml.replace(/##MRP##/g, (typeof value.mrp !== 'undefined') ? value.mrp : 0);
                tmphtml = tmphtml.replace(/##DISCOUNT##/g, (typeof value.discount !== 'undefined') ? value.discount : 0);
                tmphtml = tmphtml.replace(/##ID##/g, (typeof value.id !== 'undefined') ? value.id : '');
                $('#tbody-tmp').append(tmphtml);
              });
              $('#sales-order-final-form').find('#day').val((typeof data.result.reminder_day !== 'undefined') ? data.result.reminder_day : '');
              $("html, body").animate({ scrollTop: 0 }, "slow");

             /* $('.delete-permnent').show();
              $($this).closest('tr').find('.delete-permnent').hide();*/
            }else{
                return false;
            }
          },
          error: function () {
            return false;
          }
      });
    }else{
      return false;
    }
  });
  
  // save new product
  /*$("#add-product").on("submit", function(event){
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
                // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addproductmodel').modal('toggle');
                $('#add-product')[0].reset();

                $('#product_id').val(data.result);
                $('#product').val(dataarr.product_name);
                $('#mrp').val(dataarr.mrp);

                $('#btn-add-tmp').prop('disabled', false);
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
              // $('#addvendor-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addproduct').html('Save');
              $('#btn-addproduct').prop('disabled', false);
            }
        });

  }); */
  
  // save customer to database

  $("#add-customer-form").on("submit", function(event){

        event.preventDefault();
        var data = $(this).serializeArray();
        
       // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
       // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var dataarr = JSON.parse('{"' + decodeURI($(this).serialize()).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
          
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
                
                console.log('successfulyy added..');
               // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
              //  $('#errormsg').html(htmlsuccess);
                 showSuccessToast(data.message);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#add_customer_model').modal('toggle');
                $('#add-customer-form')[0].reset();

            
                  // add customer value
                $('#customer_id').append($('<option>', { 
                value: data.result,
                text: dataarr.name
                }));


              }else{
                showDangerToast(data.message);
              //  htmlerror =  htmlerror.replace("##MSG##", data.message);
              //  $('#addcustomer-errormsg').html(htmlerror);
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

    // FOR PRODUCT POPUP OPENING STOCK RS OPENING QTY * INWART RATE = OPENING STOCK RS
    $('body').on('keyup', '#opening_qty, #inward_rate', function () {
        var openingqty = $('#add-product').find('#opening_qty').val();
        openingqty = (typeof openingqty !== 'undefined' && !isNaN(openingqty) && openingqty !== '') ? openingqty : 0;
        
        var inwartrate = $('#add-product').find('#inward_rate').val();
        inwartrate = (typeof inwartrate !== 'undefined' && !isNaN(inwartrate) && inwartrate !== '') ? inwartrate : 0;
        
        var opening_stock_rs = (openingqty*inwartrate);
        $('#add-product').find('#opening_stock_rs').val(opening_stock_rs);
    });

});