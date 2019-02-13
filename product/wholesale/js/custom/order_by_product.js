$( document ).ready(function() {
    var cur_statecode = $('#cur_statecode').val();
    
    var table = $('.datatable').DataTable( {
        "ajax": "ajax.php?action=getOrder&type=2",
        "columns": [
            { "data": "no" },
            { "data": "order_date" },
            { "data": "vendor_name" },
            { "data": "total_order" },
            { "data" : "id",
                "render": function (data)
                {
                    // return '<button data-id='+data.vendor_id+' data-group='+data.group+' class="btn btn-success p-2 edit-permnent"><i class="icon-pencil mr-0"></i></button> <a href="order-print.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-primary p-2" target="_blank"><i class="fa fa-print mr-0"></i></a> <a href="order-by-product.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-warning p-2" title="Email"><i class="fa fa-envelope mr-0"></i></a> <a href="javascript:void(0);" data-id="'+data.vendor_id+'" data-group="'+data.group+'" class="btn btn-danger btn-reminder p-2" title="Reminder"><i class="fa fa-bell-o mr-0"></i></a> <input type="text" class="form-control input-reminder onlynumber" placeholder="day" value="'+data.reminder_day+'" data-parsley-type="number" style="width: 40%;display:none;"> <small class="reminder-error"></small>';
                    return '<button data-id='+data.vendor_id+' data-group='+data.group+' class="btn btn-success p-2 edit-permnent"><i class="icon-pencil mr-0"></i></button> <a href="order-print.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-primary p-2" target="_blank"><i class="fa fa-print mr-0"></i></a> <a href="order-by-product.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-warning p-2" title="Email"><i class="fa fa-envelope mr-0"></i></a>';
                }

            }

        ],
        //"order": [[1, 'asc']]
    });
    
    $('body').on('click', '.btn-reminder', function () {
        $(this).closest('tr').find('.input-reminder').toggle();
    });

    
    $('body').on('change', '#product_id', function (e) {
      var product_id = $(this).val();
      var product_name = $("#product_id option:selected").attr('data-name');
      $('#search').val(product_name);
      if(product_id != ''){
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'product_id':product_id,'action':'getProductById'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                $('#generic_name').val(data.result.generic_name);
                $('#mfg_co').val(data.result.mfg_company);

                $('#sgst').val(data.result.sgst);
                $('#cgst').val(data.result.cgst);
                $('#igst').val(data.result.igst);
                $('#purchase_price').val(data.result.mrp);
                $('#unit').val(data.result.unit);

                getVendorByProduct(product_id);
                setGST();
                $('#btn-addtmp').prop('disabled', false);
            
              }else{
               blankfield();
              }
            },
            error: function () {
              blankfield();
            }
        });
      }else{
        blankfield();
      }

    });

    function blankfield(){
      $('#email').val(null);
      $('#mobile').val(null);
      $('#generic_name').val(null);
      $('#mfg_co').val(null);
      $('#gst').val(null);
      $('#sgst').val(null);
      $('#cgst').val(null);
      $('#igst').val(null);
      $('#purchase_price').val(null);
      $('#unit').val(null);
      $('#vendor_id').children('option:not(:first)').remove();
      $('#btn-addtmp').prop('disabled', true);
    }

  
    $('body').on('keyup', '.input-reminder', function (e) {
        var $this = $(this);
        if (e.which == 13){
            var vendor_id = $($this).closest('tr').find('.btn-reminder').attr('data-id');
            var groups = $($this).closest('tr').find('.btn-reminder').attr('data-group');
            var day = $($this).val();
            var type = 2; //1 for order by vendor
    
            if(day !== ''){
                $.ajax({
                    type: "POST",
                    url: 'ajax.php',
                    data: {'vendor_id':vendor_id, 'groups':groups, 'day':day, 'type':type, 'action':'addOrderReminder'},
                    dataType: "json",
                    success: function (data) {
                      if(data.status == true){
                        $($this).closest('tr').find('.input-reminder').toggle();
                        $($this).closest('tr').find('.reminder-error').removeClass('text-danger').addClass('text-success');
                        $($this).closest('tr').find('.reminder-error').html('Reminder Added success.');
                      }else{
                        $($this).closest('tr').find('.reminder-error').removeClass('text-success').addClass('text-danger');
                        $($this).closest('tr').find('.reminder-error').html('Reminder Added Fail!');
                      }
                    },
                    error: function () {
                      $($this).closest('tr').find('.reminder-error').removeClass('text-success').addClass('text-danger');
                      $($this).closest('tr').find('.reminder-error').html('Reminder Added Fail!');
                    }
                });
            }else{
                $($this).closest('tr').find('.reminder-error').removeClass('text-success').addClass('text-danger');
                $($this).closest('tr').find('.reminder-error').html('Reminder day is required!');
                return false;
            }
         }
    });


    /*$( "#search" ).autocomplete({
      source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query, 'type': 'product','action': 'getProductMrpGeneric'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                console.log(data);
                if(data.status == true){
                  $(".empty-message").empty();
                    result($.map(data.result, function (item) {
                      return {
                          label: item.name,
                          value: item.id,
                          generic_name: item.generic_name,
                          menufacturer_name: item.menufacturer_name,
                          cgst: item.cgst,
                          sgst: item.sgst,
                          igst: item.igst,
                          unit: item.unit,
                          mrp : item.mrp
                      }
                  }));
                }else{
                    $(".empty-message").text("No results found");
                }
              }
          });
        },
        focus: function( query, result ) {
          $( "#search" ).val( result.item.label );
          return false;
        },
        select: function( query, result ) {
            $('#product_id').val(result.item.value);
            $('#generic_name').val(result.item.generic_name);
            $('#mfg_co').val(result.item.menufacturer_name);

            $('#sgst').val(result.item.sgst);
            $('#cgst').val(result.item.cgst);
            $('#igst').val(result.item.igst);
            $('#purchase_price').val(result.item.mrp);
            $('#unit').val(result.item.unit);

            getVendorByProduct(result.item.value);
            setGST();
            $('#btn-addtmp').prop('disabled', false);
            return false;
        }
    });*/

    function getVendorByProduct(product_id) {
      var productid = (typeof product_id !== 'undefined') ? product_id : '';
      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'product_id':productid, 'action':'getVendorByProductId'},
          dataType: "json",
          success: function (data) {
            if(data.status == true){
              $('#vendor_id').children('option:not(:first)').remove();
              $.each(data.result, function (i, item) {
                $('#vendor_id').append($('<option>', { 
                    value: item.id,
                    text : item.name 
                }));
              });
            }else{
              $('#vendor_id').children('option:not(:first)').remove();
            }
          },
          error: function () {
            $('#vendor_id').children('option:not(:first)').remove();
          }
      });
      $('#statecode').val(null);
  }


  $("#vendor_id").change(function(){
      var id = $(this).val();
      var name = (id != '') ? $("#vendor_id option:selected").text() : '';
      if($.isNumeric(id)){
        $('#email').attr('readonly', true);
        $('#mobile').attr('readonly', true);
      }else{
        $('#email').attr('readonly', false);
        $('#mobile').attr('readonly', false);
      }

      $('#vendor_name').val(name);
      if($.isNumeric(id) || id == ''){
        if(id !== ''){
          $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'id':id, 'action':'getVendorDetailByVendorId'},
              dataType: "json",
              success: function (data) {
                console.log(data);
                if(data.status == true){
                  $('#email').val((typeof data.result.email !== 'undefined') ? data.result.email : '');
                  $('#mobile').val((typeof data.result.mobile !== 'undefined') ? data.result.mobile : '');
                  $('#statecode').val((typeof data.result.statecode !== 'undefined') ? data.result.statecode : '');
                  setGST();
                }else{
                  $('#email').val(null);
                  $('#mobile').val(null);
                  $('#statecode').val(null);
                  setGST();
                }
              },
              error: function () {
                  $('#email').val(null);
                  $('#mobile').val(null);
                  $('#statecode').val(null);
                  setGST();
              }
          });
        }else{
          $('#email').val(null);
          $('#mobile').val(null);
          $('#statecode').val(null);
          setGST();
        }
    }else{
      return false;
    }
  });

  function setGST(){
    var cgst = $('#cgst').val();
        cgst = (typeof cgst !== 'undefined' && cgst !== '') ? cgst : 0;
    var sgst = $('#sgst').val();
        sgst = (typeof sgst !== 'undefined' && sgst !== '') ? sgst : 0; 
    var igst = $('#igst').val();
        igst = (typeof igst !== 'undefined' && igst !== '') ? igst : 0;
    var gst = 0;

    var statecode = $('#statecode').val();
    if(statecode !== ''){
      if(statecode == cur_statecode){
        gst = parseFloat(cgst)+parseFloat(sgst);
      }else{
        gst = parseFloat(igst);
      }
    }
    $('#gst').val(gst);
  }

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
                // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addproductmodel').modal('toggle');
                $('#add-product')[0].reset();
                
                $('#byproduct_temp_form').find('#product_id').append($('<option>', { 
                      value: data.result,
                      text : dataarr.product_name 
                }));
                $('#byproduct_temp_form').find('#product_id').val(data.result).select2();
                
                //$('#product_id').val(data.result);
                //$('#search').val(dataarr.product_name);
                $('#byproduct_temp_form').find('#generic_name').val(dataarr.generic_name);
                $('#byproduct_temp_form').find('#mfg_co').val(dataarr.mfg_company);

                $('#byproduct_temp_form').find('#sgst').val(dataarr.sgst);
                $('#byproduct_temp_form').find('#cgst').val(dataarr.cgst);
                $('#byproduct_temp_form').find('#igst').val(dataarr.igst);
                $('#byproduct_temp_form').find('#purchase_price').val(dataarr.give_mrp);
                $('#byproduct_temp_form').find('#unit').val(dataarr.unit);

                $('#btn-addtmp').prop('disabled', false);
                setGST();
                getVendorByProduct('');
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

    // FOR PRODUCT POPUP OPENING STOCK RS OPENING QTY * INWART RATE = OPENING STOCK RS
    $('body').on('keyup', '#opening_qty, #inward_rate', function () {
        var openingqty = $('#add-product').find('#opening_qty').val();
        openingqty = (typeof openingqty !== 'undefined' && !isNaN(openingqty) && openingqty !== '') ? parseFloat(openingqty) : 0;
        
        var inwartrate = $('#add-product').find('#inward_rate').val();
        inwartrate = (typeof inwartrate !== 'undefined' && !isNaN(inwartrate) && inwartrate !== '') ? parseFloat(inwartrate) : 0;
        
        var opening_stock_rs = (openingqty*inwartrate);
        $('#add-product').find('#opening_stock_rs').val(opening_stock_rs.toFixed(2));
    });
    
  $("#byproduct_temp_form").on("submit", function(event){
    event.preventDefault();
    var data = $(this).serialize();
    var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
    var randomnumber = Math.floor((Math.random()*1000) + 1);
    
        
      var html = $('#tr-html').html();
      html = html.replace('<table>','');
      html = html.replace('</table>','');
      html = html.replace('<tbody>','');
      html = html.replace('</tbody>','');

      if(typeof dataarr.editid != 'undefined' && dataarr.editid != ''){
          html = html.replace('<tr id="##DATAID##">', "");
          html = html.replace("</tr>", "");
        }else{
          html = html.replace("##DATAID##", 'tr-'+randomnumber);
        }
      
      html = html.replace(/##PRODUCTNAME##/g, (typeof dataarr.search !== 'undefined') ? dataarr.search : '');
      html = html.replace(/##PRODUCTID##/g, (typeof dataarr.product_id !== 'undefined') ? dataarr.product_id : '');
      html = html.replace(/##VENDORNAME##/g, (typeof dataarr.vendor_name !== 'undefined') ? dataarr.vendor_name : '');
      html = html.replace(/##VENDORID##/g, (typeof dataarr.vendor_id !== 'undefined') ? dataarr.vendor_id : '');
      html = html.replace(/##EMAIL##/g, (typeof dataarr.email !== 'undefined') ? decodeURIComponent(dataarr.email) : '');
      html = html.replace(/##MOBILE##/g, (typeof dataarr.mobile !== 'undefined') ? dataarr.mobile : '');
      html = html.replace(/##GENERIC##/g, (typeof dataarr.generic_name !== 'undefined') ? dataarr.generic_name : '');
      html = html.replace(/##MFG##/g, (typeof dataarr.mfg_co !== 'undefined') ? dataarr.mfg_co : '');

      html = html.replace(/##GST##/g, (typeof dataarr.gst !== 'undefined') ? dataarr.gst : '');
      html = html.replace(/##SGST##/g, (typeof dataarr.sgst !== 'undefined') ? dataarr.sgst : '');
      html = html.replace(/##CGST##/g, (typeof dataarr.cgst !== 'undefined') ? dataarr.cgst : '');
      html = html.replace(/##IGST##/g, (typeof dataarr.igst !== 'undefined') ? dataarr.igst : '');
      html = html.replace(/##UNIT##/g, (typeof dataarr.unit !== 'undefined') ? dataarr.unit : '');
      html = html.replace(/##QTY##/g, (typeof dataarr.qty !== 'undefined') ? dataarr.qty : '');
      html = html.replace(/##PURCHASEPRICE##/g, (typeof dataarr.purchase_price !== 'undefined') ? dataarr.purchase_price : '');
      html = html.replace(/##STATECODE##/g, (typeof dataarr.statecode !== 'undefined') ? dataarr.statecode : '');
      html = html.replace(/##EDITID##/g, (typeof dataarr.id !== 'undefined') ? dataarr.id : '');


      if($('#tbody-tmp tr').length == 0){
        $('#tmpdata-div').show();
      }

      if(typeof dataarr.editid != 'undefined' && dataarr.editid != ''){
        $('#'+dataarr.editid).html(html);
        $('#'+dataarr.editid).css("background-color", "#FFFFFF");
      }else{
        $('#tbody-tmp').append(html);
      }
      
      //$('#tbody-tmp').append(html);
      $('#byproduct_temp_form')[0].reset();
      $("#vendor_id").val('').trigger('change');
      $('#vendor_id').children('option:not(:first)').remove().trigger('change');
      $('#product_id').val('').trigger('change');
      $('#vendor_name').val(null);
      $('#editid').val(null);
      $('#id').val(null);

      $('#sgst').val(null);
      $('#cgst').val(null);
      $('#igst').val(null);
      $('#statecode').val(null);

      $('#btn-addtmp').prop('disabled', true);
  });

  function updateorder(data){
    // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    
    $.ajax({
        type: "POST",
        url: 'ajax.php',
        data: {'action':'updateOrder', 'data': data},
        dataType: "json",
        beforeSend: function() {
          $('#btn-addtmp').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
          $('#btn-addtmp').prop('disabled', true);
        },
        success: function (data) {
          if(data.status == true){
            showSuccessToast(data.message);
            // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
            // $('#errormsg').html(htmlsuccess);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#btn-addtmp').html('Add');
            //$('#btn-addtop').prop('disabled', false);

            // reset form
            $("#vendor_id").val('').trigger('change');
            $("#byproduct_temp_form").find('input[type=hidden]').val(null);
            $('#byproduct_temp_form')[0].reset();
            $('.delete-permnent').show();
            table.ajax.reload();
          }else{
            showDangerToast(data.message);
            // htmlerror =  htmlerror.replace("##MSG##", data.message);
            // $('#errormsg').html(htmlerror);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#btn-addtmp').html('Update');
            $('#btn-addtmp').prop('disabled', false);
          }
        },
        error: function () {
                showDangerToast('Somthing Want Wrong!');
          // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
          // $('#errormsg').html(htmlerror);
          $("html, body").animate({ scrollTop: 0 }, "slow");

          $('#btn-addtmp').html('Update');
          $('#btn-addtmp').prop('disabled', false);
        }
      });
    return false;
  }
  
  $('body').on('click', '.delete-temp', function () {
    /*--------------------------------------------------------*/
    var $this = $(this);
    // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    if (confirm('Are sure want delete this order?')) {
        var editid = $($this).closest('tr').find('.editid').val();

        if(editid != ''){
            $.ajax({
              url: "ajax.php",
              data: {'id': editid, 'action': 'deleteOrder'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                  $($this).closest ('tr').remove ();
                  var rowCount = $('#tbody-tmp tr').length;
                  if(rowCount == 0){
                    $('#tmpdata-div').hide();
                  }
                    showSuccessToast(data.message);
                  // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                  // $('#errormsg').html(htmlsuccess);
                  $("html, body").animate({ scrollTop: 0 }, "slow");
                  table.ajax.reload();
                  return false;
                }else{
                     showDangerToast(data.message);
                    // htmlerror =  htmlerror.replace("##MSG##", data.message);
                    // $('#errormsg').html(htmlerror);
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    return false;
                }
              },
              error: function () {
                showDangerToast('Somthing Want Wrong! Try again.');
                // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
                // $('#errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
              }
          });
        }else{
          $($this).closest ('tr').remove ();
          var rowCount = $('#tbody-tmp tr').length;
          if(rowCount == 0){
            $('#tmpdata-div').hide();
          }
        }
    }else{
      return false;
    }
    /*--------------------------------------------------------*/
  });

  $('body').on('click', '.edit-temp', function () {
    $('#vendor_loader').show();

    var dataid = $(this).closest ('tr').attr('id');
    var product_name = $(this).closest ('tr').find('.product_name').val();
    var product_id = $(this).closest ('tr').find('.product_id').val();
    var vendor_name = $(this).closest ('tr').find('.vendor_name').val();
    var vendor_id = $(this).closest ('tr').find('.vendor_id').val();
    var email = $(this).closest ('tr').find('.email').val();
    var mobile = $(this).closest ('tr').find('.mobile').val();
    var generic = $(this).closest ('tr').find('.generic').val();
    var mfg = $(this).closest ('tr').find('.mfg').val();

    var gst = $(this).closest ('tr').find('.gst').val();
    var cgst = $(this).closest ('tr').find('.cgst').val();
    var sgst = $(this).closest ('tr').find('.sgst').val();
    var igst = $(this).closest ('tr').find('.igst').val();
    var purchase_price = $(this).closest ('tr').find('.purchase_price').val();
    var unit = $(this).closest ('tr').find('.unit').val();
    var qty = $(this).closest ('tr').find('.qty').val();
    var statecode = $(this).closest ('tr').find('.statecode').val();
    var editid = $(this).closest ('tr').find('.editid').val();//edit id

    

    // set value to form
    $('#byproduct_temp_form').find('#editid').val(dataid);
    $('#byproduct_temp_form').find('#search').val(product_name);
    // $('#byproduct_temp_form').find('#product_id').val(product_id);
    $('#byproduct_temp_form').find('#product_id').val(product_id).select2();
    $('#byproduct_temp_form').find('#vendor_name').val(vendor_name);
    $('#byproduct_temp_form').find('#email').val(email);
    $('#byproduct_temp_form').find('#mobile').val(mobile);
    $('#byproduct_temp_form').find('#generic_name').val(generic);
    $('#byproduct_temp_form').find('#mfg_co').val(mfg);

    $('#byproduct_temp_form').find('#gst').val(gst);
    $('#byproduct_temp_form').find('#cgst').val(cgst);
    $('#byproduct_temp_form').find('#sgst').val(sgst);
    $('#byproduct_temp_form').find('#igst').val(igst);
    $('#byproduct_temp_form').find('#purchase_price').val(purchase_price);
    $('#byproduct_temp_form').find('#unit').val(unit);
    $('#byproduct_temp_form').find('#qty').val(qty);
    $('#byproduct_temp_form').find('#statecode').val(statecode);
    $('#byproduct_temp_form').find('#id').val(editid);


    if($.isNumeric(vendor_id)){
      getVendorByProduct(product_id);
        $('#email').attr('readonly', true);
        $('#mobile').attr('readonly', true);
      }else{
        $('#vendor_id').children('option:not(:first)').remove().trigger('change');
        $('#vendor_id').append($('<option>', { 
              value: vendor_id,
              text : vendor_name 
            }));
        $('#email').attr('readonly', false);
        $('#mobile').attr('readonly', false);
      }
      setTimeout( function(){
          $('#byproduct_temp_form').find('#vendor_id').val(vendor_id).trigger('change');
          $('#vendor_loader').hide();
      },1000);
      
      var deletebtn = $(this).closest('tr').find('.delete-temp');
      $(deletebtn).hide();
      $('.delete-temp').not(deletebtn).show();
      $('#tbody-tmp tr').css("background-color", "#FFFFFF")
      $('#'+dataid).css("background-color", "#A9A9A9");
      $("html, body").animate({ scrollTop: 0 }, "slow");

    $('#btn-addtmp').prop('disabled', false);
  });

  $("#add-byproduct-form").on("submit", function(event){
    event.preventDefault();
      var data = $(this).serialize();
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

      $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addOrder', 'type': 2,'data': data},
            dataType: "json",
            beforeSend: function() {
              $('.btn-savebyproduct').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('.btn-savebyproduct').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('.btn-savebyproduct').html('Save');
                $('.btn-savebyproduct').prop('disabled', false);
                $('#tbody-tmp').empty();
                $('#add-byproduct-form').find('#day').val(null);
                $('#tmpdata-div').hide();
                table.ajax.reload();
              }else{
                showDangerToast(data.message);
                // htmlerror =  htmlerror.replace("##MSG##", data.message);
                // $('#errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('.btn-savebyproduct').html('Save');
                $('.btn-savebyproduct').prop('disabled', false);
              }
            },
            error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('.btn-savebyproduct').html('Save');
              $('.btn-savebyproduct').prop('disabled', false);
            }
        });

  });

  $('body').on('click', '.delete-permnent', function () {
      var id = $(this).attr('data-id');
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var conf = confirm('Are you sure want to delete this record?');
      if(conf && id !== ''){
        $.ajax({
            url: "ajax.php",
            data: {'id': id, 'action': 'deleteOrder'},            
            dataType: "json",
            type: "POST",
            success: function (data) {
              if(data.status == true){
                showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                table.ajax.reload();
              }else{
                    showDangerToast(data.message);
                  // htmlerror =  htmlerror.replace("##MSG##", data.message);
                  // $('#errormsg').html(htmlerror);
                  $("html, body").animate({ scrollTop: 0 }, "slow");
              }
            },
            error: function () {
                showDangerToast('Somthing Want Wrong!');
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
    var vendor_id = $(this).attr('data-id');
    var group = $(this).attr('data-group');
    var $this = $(this);

    if(vendor_id !== '' && group !== ''){
      $.ajax({
          url: "ajax.php",
          data: {'vendor_id': vendor_id, 'group':group, 'action': 'getDataEditByproduct'},            
          dataType: "json",
          type: "POST",
          success: function (data) {
            if(data.status == true){
              $('#tmpdata-div').show();
              $('#tbody-tmp').empty();
              /*---------------------------------------------------------------------------------------------------------------*/
              var html = $('#tr-html').html();
              html = html.replace('<table>','');
              html = html.replace('</table>','');
              html = html.replace('<tbody>','');
              html = html.replace('</tbody>','');

              $.each(data.result.data, function (key, val) {
                var tmphtml = html;
                var randomnumber = Math.floor((Math.random()*1000) + 1);
                tmphtml = tmphtml.replace(/##DATAID##/g, randomnumber);
                tmphtml = tmphtml.replace(/##EDITID##/g, (typeof val.id !== 'undefined') ? val.id : '');
                tmphtml = tmphtml.replace(/##PRODUCTNAME##/g, (typeof val.product_name !== 'undefined') ? val.product_name : '');
                tmphtml = tmphtml.replace(/##PRODUCTID##/g, (typeof val.product_id !== 'undefined') ? val.product_id : '');
                tmphtml = tmphtml.replace(/##VENDORNAME##/g, (typeof val.vendor_name !== 'undefined') ? val.vendor_name : '');
                tmphtml = tmphtml.replace(/##VENDORID##/g, (typeof val.vendor_id !== 'undefined') ? val.vendor_id : '');
                tmphtml = tmphtml.replace(/##STATECODE##/g, (typeof val.state_code !== 'undefined') ? val.state_code : '');
                tmphtml = tmphtml.replace(/##EMAIL##/g, (typeof val.email !== 'undefined') ? val.email : '');
                tmphtml = tmphtml.replace(/##MOBILE##/g, (typeof val.mobile !== 'undefined') ? val.mobile : '');
                tmphtml = tmphtml.replace(/##GENERIC##/g, (typeof val.generic_name !== 'undefined') ? val.generic_name : '');
                tmphtml = tmphtml.replace(/##MFG##/g, (typeof val.mfg_company !== 'undefined') ? val.mfg_company : '');

                tmphtml = tmphtml.replace(/##GST##/g, (typeof val.gst !== 'undefined') ? val.gst : '');

                  if($('#currentstate').val() == val.state_code){
                    tmphtml = tmphtml.replace(/##SGST##/g, (typeof val.gst !== 'undefined') ? val.gst/2 : '');
                    tmphtml = tmphtml.replace(/##CGST##/g, (typeof val.gst !== 'undefined') ? val.gst/2 : '');
                    tmphtml = tmphtml.replace(/##IGST##/g, 0);
                  }else{
                    tmphtml = tmphtml.replace(/##SGST##/g, 0);
                    tmphtml = tmphtml.replace(/##CGST##/g, 0);
                    tmphtml = tmphtml.replace(/##IGST##/g, (typeof val.gst !== 'undefined') ? val.gst/2 : '');
                  }
                  

                tmphtml = tmphtml.replace(/##UNIT##/g, (typeof val.unit !== 'undefined') ? val.unit : '');
                tmphtml = tmphtml.replace(/##QTY##/g, (typeof val.qty !== 'undefined') ? val.qty : '');
                tmphtml = tmphtml.replace(/##PURCHASEPRICE##/g, (typeof val.purchase_price !== 'undefined') ? val.purchase_price : '');
                $('#tbody-tmp').append(tmphtml);
              });
                $('#add-byproduct-form').find('#day').val((typeof data.result.reminder_day !== 'undefined') ? data.result.reminder_day : '');
              /*---------------------------------------------------------------------------------------------------------------*/
              $("html, body").animate({ scrollTop: 0 }, "slow");
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


});