$( document ).ready(function() {

  var tbl_template = $('.template-table').DataTable( {
      "ajax": "ajax.php?action=getAllTemplate",
      "columns": [
          { "data": "sr_no" },
          { "data": "no" },
          { "data": "date" },
          { "data": "name" },
          { "data" : "status",
              "render": function (data)
              {
                var status = (data.status == 1) ? 'active' : '';
                return '<button type="button" class="btn btn-sm btn-toggle changestatus '+status+'" data-table="sales_template" data-id="'+data.id+'" data-toggle="button" aria-pressed="'+data.status+'" autocomplete="off"><div class="handle"></div></button>';
              }

          }

      ],
      //"order": [[1, 'asc']]
  });

  var tbl_order = $('.order-table').DataTable( {
      "ajax": "ajax.php?action=getAllTemplateDetail",
      "columns": [
          { "data": "no" },
          {"data" : "customer_name"},
          { "data": "template_name" },
          { "data": "product_name" },
          { "data": "batch_no" },
          { "data": "qty" },
          { "data" : "id",
              "render": function (data)
              {
                return '<button data-id='+data+' class="btn btn-success p-2 edit-permnent"><i class="icon-pencil mr-0"></i></button> <button data-id='+data+' class="btn btn-primary p-2 delete-permnent"><i class="icon-trash mr-0"></i></button>';
              }

          }

      ],
      //"order": [[1, 'asc']]
  });

    $("#add-new-template-form").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
     // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
       // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

        $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'action':'addNewTemplate', 'data': data},
          dataType: "json",
          beforeSend: function() {
            $('#btn-add-template').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
            $('#btn-add-template').prop('disabled', true);
          },
          success: function (data) {
            if(data.status == true){
                showSuccessToast(data.message);
              //htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
              //$('#template-errormsg').html(htmlsuccess);
              $('#add-new-template-form')[0].reset();
              
              $('#template_id').append($('<option>', { 
                  value: data.result,
                  text : dataarr['name'] 
              }));
              
              setInterval(function(){
                 $('#add-template-model').modal('hide');
                 $('#template_id').val(data.result).trigger('change');
              }, 1000);
              tbl_template.ajax.reload();
              return false;
            }else{
              //htmlerror =  htmlerror.replace("##MSG##", data.message);
              //$('#template-errormsg').html(htmlerror);
                showDangerToast(data.message);
            }
            $('#btn-add-template').html('Save');
            $('#btn-add-template').prop('disabled', false);
          },
          error: function () {
                showDangerToast('Somthing Want Wrong!');
           // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
            //$('#template-errormsg').html(htmlerror);

            $('#btn-add-template').html('Save');
            $('#btn-add-template').prop('disabled', false);
          }
      });

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
                this.value = (ui.item ? ui.item.name : '');
                //$('#results').text(ui.item ? 'Selected: ' + ui.item.name + ', ' + ui.item.purchase_id + ', ' + ui.item.batch : 'Nothing selected, input was ' + this.value);
                
                $('#product_id').val(ui.item.id);
                /*$('#mrp').val(ui.item.mrp);*/
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
                            $('#product-error').empty();
                            response(data.result);
                        }else{
                            $('#product-error').text("No results found");
                        }
                    }
                });
            }
        });
    });

    // SAVE TEMPORARY ORDER DATA
    $("#tmp-add-order-form").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serializeArray();
        var serialdata = $(this).serialize();
      if(data.length){

        var post = [];
        post['template_name'] = $("#template_id option:selected").text();
        $.each(data, function (key, val) {
            post[val.name] = val.value;
        });

        if(typeof post.id !== 'undefined' && post.id != ''){
          updateTemplateOrder(serialdata);
        }else{

          reset();
        
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
          html = html.replace(/##TEMPLATENAME##/g, (typeof post.template_name !== 'undefined') ? post.template_name : '');
          html = html.replace("##TEMPLATEID##", (typeof post.template_id !== 'undefined') ? post.template_id : '');
          html = html.replace(/##PRODUCTNAME##/g, (typeof post.product !== 'undefined') ? post.product : '');
          html = html.replace("##PRODUCTID##", (typeof post.product_id !== 'undefined') ? post.product_id : '');
          html = html.replace(/##QTY##/g, (typeof post.qty !== 'undefined') ? post.qty : 0);
          
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
        }
      }else{
        return false;
      }

    });

    function updateTemplateOrder(data){
      //var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      //var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    
      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'action':'updateTemplateOrder', 'data': data},
          dataType: "json",
          beforeSend: function() {
            $('#btn-add-tmp').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
            $('#btn-add-tmp').prop('disabled', true);
          },
          success: function (data) {
            if(data.status == true){
              showSuccessToast(data.message);
              //htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
              //$('#errormsg').html(htmlsuccess);
              $("html, body").animate({ scrollTop: 0 }, "slow");
              $('#btn-add-tmp').html('Add');

              // reset form data
              reset();
              $('.delete-permnent').show();
              tbl_order.ajax.reload();
            }else{
              showDangerToast(data.message);
              //htmlerror =  htmlerror.replace("##MSG##", data.message);
              //$('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");
              $('#btn-add-tmp').html('Update');
              $('#btn-add-tmp').prop('disabled', false);
            }
          },
          error: function () {
              showDangerToast('Somthing Want Wrong!');
            //htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
            //$('#errormsg').html(htmlerror);
            $("html, body").animate({ scrollTop: 0 }, "slow");

            $('#btn-add-tmp').html('Update');
            $('#btn-add-tmp').prop('disabled', false);
          }
      });
      return false;
    }

    function reset(){
      $('#tmp-add-order-form')[0].reset();
      $('#tmp-add-order-form').find('input[type=hidden]').val(null);
      $("#btn-add-tmp").prop("disabled",true);
      $("#template_id").val('').trigger('change');
    }

    // REMOVE TEMP TR
    $('body').on('click', '.delete-temp', function () {
        $(this).closest ('tr').remove ();
        var rowCount = $('#tbody-tmp tr').length;
        if(rowCount == 0){
          $('#tmpdata-div').hide();
        }
    });

    // EDIT TEMP DATA
    $('body').on('click', '.edit-temp', function () {

      var dataid = $(this).closest ('tr').attr('id');
       var customer_name = $(this).closest('tr').find('.customer_name').val();
      var customer_id = $(this).closest ('tr').find('.customer_id').val();
      var template_name = $(this).closest ('tr').find('.template_name').val();
      var template_id = $(this).closest ('tr').find('.template_id').val();
      var product_name = $(this).closest ('tr').find('.product_name').val();
      var product_id = $(this).closest ('tr').find('.product_id').val();
      var qty = $(this).closest ('tr').find('.qty').val();


      // set all value in form

      $('#tmp-add-order-form').find('#editid').val(dataid);
       $('#tmp-add-order-form').find('#customer_name').val(customer_name);
      $('#tmp-add-order-form').find('#customer_id').val(customer_id);
      $('#tmp-add-order-form').find('#template_id').val(template_id).trigger('change');
      $('#tmp-add-order-form').find('#product').val(product_name);
      $('#tmp-add-order-form').find('#product_id').val(product_id);
      $('#tmp-add-order-form').find('#qty').val(qty);

      var deletebtn = $(this).closest('tr').find('.delete-temp');
      $(deletebtn).hide();
      $('.delete-temp').not(deletebtn).show();

      $('#tbody-tmp tr').css("background-color", "#FFFFFF")
      $('#'+dataid).css("background-color", "#A9A9A9");
      $("html, body").animate({ scrollTop: 0 }, "slow");
      $('#btn-add-tmp').prop('disabled', false);
      return false;
    });

    // FINALLY SAVE PRODUCT ON DATA BASE
    $("#order-final-form").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serialize();
        // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addTemplateDetail', 'data': data},
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
                $('#tmpdata-div').hide();
                tbl_order.ajax.reload();
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
              showDangerToast('Somthing Want Wrong!');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('.btn-saveproduct').html('Save');
              $('.btn-saveproduct').prop('disabled', false);
            }
        });
    });

    // FINALLY DELETE ORDER
    $('body').on('click', '.delete-permnent', function () {
      var id = $(this).attr('data-id');
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var conf = confirm('Are you sure want to delete this record?');
      
      if(conf && id !== ''){
        $.ajax({
            url: "ajax.php",
            data: {'id': id, 'action': 'deleteTemplateOrder'},            
            dataType: "json",
            type: "POST",
            success: function (data) {
              if(data.status == true){
                 showSuccessToast(data.message);
               // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
               // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                tbl_order.ajax.reload();
              }else{
                showDangerToast(data.message);
                 // htmlerror =  htmlerror.replace("##MSG##", data.message);
                //  $('#errormsg').html(htmlerror);
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

    // FINALLY EDIT ORDER
    $('body').on('click', '.edit-permnent', function () {
        var id = $(this).attr('data-id');
        var $this = $(this);
    
        if(id !== ''){
          $.ajax({
              url: "ajax.php",
              data: {'id': id, 'action': 'getEditDataForTemplateOrder'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                  
                  /*-----------fill data in edit form start--------------*/
                  $('#tmp-add-order-form').find('#customer_name').val(data.result.customer_name);
                  $('#tmp-add-order-form').find('#customer_id').val(data.result.customer_id);
                  $('#tmp-add-order-form').find('#template_id').val(data.result.template_id).trigger('change');
                  $('#tmp-add-order-form').find('#product').val(data.result.product_name);
                  $('#tmp-add-order-form').find('#product_id').val(data.result.product_id);
                  $('#tmp-add-order-form').find('#qty').val(data.result.qty);
                  $('#tmp-add-order-form').find('#id').val(data.result.id);
                  /*-----------fill data in edit form end--------------*/
                  
                    $('#btn-add-tmp').prop('disabled', false);
                    $('#btn-add-tmp').html('Update');
                    $("html, body").animate({ scrollTop: 0 }, "slow");
    
                    $('.delete-permnent').show();
                    $($this).closest('tr').find('.delete-permnent').hide();
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
               //  $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addproductmodel').modal('toggle');
                $('#add-product')[0].reset();
    
                $('#product_id').val(data.result);
                $('#product').val(dataarr.product_name);
                
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
    });
  
  
   // ----------------view customer-------------------------------- 


    $('body').on('keyup click', '#customer_name', function () {
        var $this = $(this);
    
        $(this).mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
            showHeader: true,
            columns: [{
                name: 'Name',
                width: '200px',
                valueField: 'name'
            },{
                name: 'Mobile',
                width: '200px',
                valueField: 'mobile'
            },{
                name: 'Customer ID',
                width: '200px',
                valueField: 'customer_id'
            }
           
            ],
    
            // Event handler for when a list item is selected.
            select: function (event, ui) {
                this.value = (ui.item ? ui.item.name : '');
                $('#customer_id').val(ui.item.id);
                $('#customer_mobile').val(ui.item.mobile);
                // $('#statecode').val(ui.item.state);
                // if(ui.item.customer_id !== ''){
                //   $('#customer_u_id').val(ui.item.customer_id);
                // }
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
                            $('#customer-error').empty();
                            $($this).closest('tr').find('#customer-error').empty();
                            response(data.result)
                        }else{
                            $('#customer-error').text("No results found");
                        }
                    },error: function () {
                      $('#customer-error').text("No results found");
                    }
                });
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