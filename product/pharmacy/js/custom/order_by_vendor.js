$( document ).ready(function() {

  // table listing
    var cur_statecode = $('#cur_statecode').val();
    
    var table = $('.datatable').DataTable( {
        "ajax": "ajax.php?action=getOrder&type=1",
        "columns": [
            { "data": "no" },
            { "data": "order_date" },
            { "data": "vendor_name" },
            { "data": "total_order" },
            { "data" : "id",
                "render": function (data)
                {
                    // return '<button data-id='+data.vendor_id+' data-group='+data.group+' class="btn btn-success p-2 edit-permnent"><i class="icon-pencil mr-0"></i></button> <a href="order-print.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-primary p-2" target="_blank"><i class="fa fa-print mr-0"></i></a> <a href="order.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-warning p-2" title="Email"><i class="fa fa-envelope mr-0"></i></a>';
                    // return '<button title="Edit" data-id='+data.vendor_id+' data-group='+data.group+' class="btn btn-success p-2 edit-permnent"><i class="icon-pencil mr-0"></i></button> <a href="order-print.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-primary p-2" title="Print" target="_blank"><i class="fa fa-print mr-0"></i></a> <a href="order.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-warning p-2" title="Email"><i class="fa fa-envelope mr-0"></i></a> <a href="javascript:void(0);" data-id="'+data.vendor_id+'" data-group="'+data.group+'" class="btn btn-danger btn-reminder p-2" title="Reminder"><i class="fa fa-bell-o mr-0"></i></a> <input type="text" class="form-control input-reminder onlynumber" placeholder="day" value="'+data.reminder_day+'" data-parsley-type="number" style="width: 40%;display:none;"> <small class="reminder-error"></small>';
                    return '<button title="Edit" data-id='+data.vendor_id+' data-group='+data.group+' class="btn btn-success p-2 edit-permnent"><i class="icon-pencil mr-0"></i></button> <a href="order-print.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-primary p-2" title="Print" target="_blank"><i class="fa fa-print mr-0"></i></a> <a href="order.php?id='+data.vendor_id+'&group='+data.group+'" class="btn btn-warning p-2" title="Email"><i class="fa fa-envelope mr-0"></i></a>';
                }

            }

        ],
        //"order": [[1, 'asc']]
    });
    
    $('body').on('click', '.btn-reminder', function () {
        $(this).closest('tr').find('.input-reminder').toggle();
    });

  
    $('body').on('keyup', '.input-reminder', function (e) {
        var $this = $(this);
        if (e.which == 13){
            var vendor_id = $($this).closest('tr').find('.btn-reminder').attr('data-id');
            var groups = $($this).closest('tr').find('.btn-reminder').attr('data-group');
            var day = $($this).val();
            var type = 1; //1 for order by vendor
    
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

  $('#product_id').on('change', function() {
    var product_id = $(this).val();
    getProduct(product_id);
  });

  $('#selectsearch').on('change', function() {
      var val = $(this).val();
      if(val == 'product'){
        $('#search-lable').html('Product Name')
      }else if(val == 'mrp'){
        $('#search-lable').html('MRP')
      }else{
        $('#search-lable').html('Generic Name')
      }
      $('#search').val(null);
      $('#product_id').val(null);
      $('#generic-name').html(null);
      $('#menufacturer-name').html(null);
      $('#generic-name-input').val(null);
      $('#menufacturer-name-input').val(null);
      $('#product_name').val(null);
      $('#purchase_price').val(0);
      $('#gst').val(0);
      $('#unit').val(0);
      $('#qty').val(0);
      getAllProduct($('#vendor_id').val(), val);
  });

  function blankinput(){
    $('#product_name').val(null);
    $('#generic-name').html(null);
    $('#menufacturer-name').html(null);
    $('#generic-name-input').val(null);
    $('#menufacturer-name-input').val(null);
    $('#gst').val(0);
    $('#unit').val(0);
    $('#purchase_price').val(0);
    $('#btn-addtop').prop('disabled', true);
  }

  function getProduct(id = null){

      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'product_id':id, 'action':'getProductById'},
          dataType: "json",
          success: function (data) {
            if(data.status == true){

            var statecode = $('#statecode').val();
            var gst = 0;
            
            $('#product_name').val(data.result.product_name);
            $('#generic-name').html(data.result.generic_name);
            $('#menufacturer-name').html(data.result.mfg_company);
            $('#generic-name-input').val(data.result.generic_name);
            $('#menufacturer-name-input').val(data.result.mfg_company);

            if(statecode == cur_statecode){
              gst = parseFloat(data.result.cgst)+parseFloat(data.result.sgst);
            }else{
              gst = parseFloat(data.result.igst);
            }
            $('#gst').val(gst);
            $('#unit').val(data.result.unit);
            $('#purchase_price').val(data.result.mrp);
            $('#btn-addtop').prop('disabled', false);

            }else{
              blankinput();
            }
          },
          error: function () {
            blankinput();
          }
      });
  }

  function getAllProduct(vendor_id = null, type = null){
      blankinput();
      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'vendor_id':vendor_id, 'type': type, 'action':'getVendorByProduct'},
          dataType: "json",
          /*beforeSend: function() {
            $('#product_loader').show();
          },*/
          success: function (data) {
            //$('#product_loader').hide();
            if(data.status == true){
              $('#product_id').children('option:not(:first)').remove();

              $.each(data.result, function (i, item) {
                $('#product_id').append($('<option>', { 
                    value: item.id,
                    text : item.name 
                }));
              });
            }else{
              $('#product_id').children('option:not(:first)').remove();
            }
          },
          error: function () {
            $('#product_id').children('option:not(:first)').remove();
            //$('#product_loader').hide();
          }
      });
  }

  $('#vendor_id').on('change', function() {
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
                    $('#vendor_name').val(data.result.vendor_name);
                  }else{
                    $('#statecode').val('');
                    $('#vendor_name').val('');
                  }
                },
                error: function () {
                  $('#statecode').val('');
                  $('#vendor_name').val('');
                }
            });
            getAllProduct(vendor_id, $('#selectsearch').val());
        }else{
          $('#statecode').val('');
          $('#vendor_name').val('');
          $('#product_id').children('option:not(:first)').remove();
        }
  });

  $( "#search" ).autocomplete({
      source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query, 'type': $('#selectsearch').val(), 'vendor_id': $('#vendor_id').val(), 'action': 'getVendorByProduct'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                  $(".empty-message").empty();
                    result($.map(data.result, function (item) {
                      return {
                          label: item.name,
                          value: item.id,
                          generic_name: item.generic_name,
                          menufacturer_name: item.menufacturer_name,
                          igst: item.igst,
                          cgst: item.cgst,
                          sgst: item.sgst,
                          unit: item.unit,
                          mrp: item.mrp,
                          productname: item.productname
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
          var statecode = $('#statecode').val();
          var gst = 0;
            $('#product_id').val(result.item.value);
            $('#product_name').val(result.item.productname);
            $('#generic-name').html(result.item.generic_name);
            $('#menufacturer-name').html(result.item.menufacturer_name);
            $('#generic-name-input').val(result.item.generic_name);
            $('#menufacturer-name-input').val(result.item.menufacturer_name);

            if(statecode == cur_statecode){
              gst = parseFloat(result.item.cgst)+parseFloat(result.item.sgst);
            }else{
              gst = parseFloat(result.item.igst);
            }
            $('#gst').val(gst);
            $('#unit').val(result.item.unit);
            $('#purchase_price').val(result.item.mrp);
            $('#btn-addtop').prop('disabled', false);
            return false;
        }
    });

    // Add Vendor data

    $("#add_byvendor_temp").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serializeArray();
      var serialdata = $(this).serialize();
      if(data.length){

          var post = [];
          $.each(data, function (key, val) {
              post[val.name] = val.value;
          });

          // reset form data
          $("#vendor_id").val('').trigger('change');
          $("#selectsearch").val('product').trigger('change');
          $("#add_byvendor_temp").find('input[type=hidden]').val(null);
          $('#add_byvendor_temp')[0].reset();

        
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
          html = html.replace(/##VENDORNAME##/g, (typeof post.vendor_name !== 'undefined') ? post.vendor_name : '');
          html = html.replace("##VENDORID##", (typeof post.vendor_id !== 'undefined') ? post.vendor_id : '');
          html = html.replace(/##UNIT##/g, (typeof post.unit !== 'undefined') ? post.unit : 0);
          html = html.replace("##STATECODE##", (typeof post.statecode !== 'undefined') ? post.statecode : '');
          html = html.replace(/##QTY##/g, (typeof post.qty !== 'undefined') ? post.qty : 0);
          html = html.replace(/##MRP##/g, (typeof post.purchase_price !== 'undefined') ? post.purchase_price : '');
          html = html.replace(/##PRODUCTNAME##/g, (typeof post.product_name !== 'undefined') ? post.product_name : '');
          html = html.replace("##PRODUCTID##", (typeof post.product_id !== 'undefined') ? post.product_id : '');
          html = html.replace(/##GST##/g, (typeof post.gst !== 'undefined') ? post.gst : 0);
          html = html.replace("##GENERICNAME##", (typeof post.generic_name !== 'undefined') ? post.generic_name : 0);
          html = html.replace("##MANUFACTURERNAME##", (typeof post.menufacturer_name !== 'undefined') ? post.menufacturer_name : 0);
          html = html.replace("##EDITID##", (typeof post.id !== 'undefined') ? post.id : '');
          
          
          var rowCount = $('#tbody-tmp tr').length;
          if(rowCount == 0){
            $('#tmpdata-div').show();
          }
          if(typeof post.editid != 'undefined' && post.editid != ''){
            $('#'+post.editid).html(html);
            // $('#'+post.editid).find('.delete-temp').hide();
            $('#'+post.editid).css("background-color", "#FFFFFF");
          }else{
            $('#tbody-tmp').append(html);
          }
          $('#btn-addtop').prop('disabled', true);
      }else{
        return false;
      }

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
          $('#btn-addtop').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
          $('#btn-addtop').prop('disabled', true);
        },
        success: function (data) {
          if(data.status == true){
                 showSuccessToast(data.message);
           // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
           // $('#errormsg').html(htmlsuccess);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#btn-addtop').html('Add');
            //$('#btn-addtop').prop('disabled', false);

            // reset form
            $("#vendor_id").val('').trigger('change');
            $("#selectsearch").val('product').trigger('change');
            $("#add_byvendor_temp").find('input[type=hidden]').val(null);
            $('#add_byvendor_temp')[0].reset();
            $('.delete-permnent').show();
            table.ajax.reload();
          }else{
                showDangerToast(data.message);
            // htmlerror =  htmlerror.replace("##MSG##", data.message);
           // $('#errormsg').html(htmlerror);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#btn-addtop').html('Update');
            $('#btn-addtop').prop('disabled', false);
          }
        },
        error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
          // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
          // $('#errormsg').html(htmlerror);
          $("html, body").animate({ scrollTop: 0 }, "slow");

          $('#btn-addtop').html('Update');
          $('#btn-addtop').prop('disabled', false);
        }
      });
    return false;
  }
  
  $('body').on('click', '.delete-temp', function () {
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
  });

  $('body').on('click', '.edit-temp', function () {
    $('#product_loader').show();
    var dataid = $(this).closest('tr').attr('id');
    var vendorname = $(this).closest('tr').find('.vendor_name').val();
    var vendorid = $(this).closest('tr').find('.vendor_id').val();
    var unit = $(this).closest('tr').find('.unit').val();
    var statecode = $(this).closest('tr').find('.state_code').val();
    var qty = $(this).closest ('tr').find('.qty').val();
    var purchase_price = $(this).closest('tr').find('.purchase_price').val();
    var productname = $(this).closest('tr').find('.product_name').val();
    var productid = $(this).closest('tr').find('.product_id').val();
    var gst = $(this).closest('tr').find('.gst').val();
    var generic_name = $(this).closest('tr').find('.generic_name').val();
    var menufacturer_name = $(this).closest('tr').find('.menufacturer_name').val();
    var editid = $(this).closest('tr').find('.editid').val();


    // set all value in form

    $('#add_byvendor_temp').find('#editid').val(dataid);
    $('#add_byvendor_temp').find('#vendor_name').val(vendorname);
    //$('#add_byvendor_temp').find('#vendor_id').val(vendorid);

    $('#add_byvendor_temp').find('#vendor_id').val(vendorid).trigger('change');

    $('#add_byvendor_temp').find('#unit').val(unit);
    $('#add_byvendor_temp').find('#statecode').val(statecode);
    $('#add_byvendor_temp').find('#qty').val(qty);
    $('#add_byvendor_temp').find('#purchase_price').val(purchase_price);
    $('#add_byvendor_temp').find('#product_name').val(productname);
    $('#add_byvendor_temp').find('#search').val(productname);

    // $('#add_byvendor_temp').find('#product_id').val(productid);
    setTimeout(function() {
       $('#add_byvendor_temp').find('#product_id').val(productid).select2();
       $('#product_loader').hide();
    }, 2000);
    

    $('#add_byvendor_temp').find('#gst').val(gst);
    $('#add_byvendor_temp').find('#generic-name-input').val(generic_name);
    $('#add_byvendor_temp').find('#menufacturer-name-input').val(menufacturer_name);

    $('#add_byvendor_temp').find('#generic-name').html(generic_name);
    $('#add_byvendor_temp').find('#menufacturer-name').html(menufacturer_name);
    $('#add_byvendor_temp').find('#id').val(editid);

    var deletebtn = $(this).closest('tr').find('.delete-temp');
    $(deletebtn).hide();
    $('.delete-temp').not(deletebtn).show();

    $('#tbody-tmp tr').css("background-color", "#FFFFFF")
    $('#'+dataid).css("background-color", "#A9A9A9");
    $("html, body").animate({ scrollTop: 0 }, "slow");
    $('#btn-addtop').prop('disabled', false);
    return false;
  });

  // save data in data base

  $("#add-byvendor-form").on("submit", function(event){
    event.preventDefault();
      var data = $(this).serialize();
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

      $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addOrder', 'type': 1, 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('.btn-savebyvendor').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('.btn-savebyvendor').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                 showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('.btn-savebyvendor').html('Save');
                $('.btn-savebyvendor').prop('disabled', false);
                $('#tbody-tmp').empty();
                $('#add-byvendor-form').find('#day').val(null);
                $('#tmpdata-div').hide();
                table.ajax.reload();
              }else{
                showDangerToast(data.message);
                // htmlerror =  htmlerror.replace("##MSG##", data.message);
                // $('#errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('.btn-savebyvendor').html('Save');
                $('.btn-savebyvendor').prop('disabled', false);
              }
            },
            error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
             // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
             // $('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('.btn-savebyvendor').html('Save');
              $('.btn-savebyvendor').prop('disabled', false);
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

  // SAVE DB TO ADD NEW PRODUCT
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

                //$('#product_id').val(data.result);
                $('#product_id').append($('<option>', { 
                      value: data.result,
                      text : dataarr.product_name 
                }));
                $('#product_id').val(data.result).select2();
                // $('#search').val(dataarr.product_name);
                $('#product_name').val(dataarr.product_name);
                $('#purchase_price').val(dataarr.mrp);
                $('#unit').val(dataarr.unit);
                $('#generic-name').html(dataarr.generic_name);
                $('#generic-name-input').val(dataarr.generic_name);
                $('#menufacturer-name').html(dataarr.mfg_company);
                $('#menufacturer-name-input').val(dataarr.mfg_company);

                var gst = 0;
                  if($('#statecode').val() == cur_statecode){
                    gst = parseFloat(dataarr.cgst)+parseFloat(dataarr.sgst);
                  }else{
                    gst = parseFloat(dataarr.igst);
                  }
                $('#gst').val(gst);

                $('#btn-addtop').prop('disabled', false);
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
                showDangerToast('Somthing Want Wrong!');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#addvendor-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addproduct').html('Save');
              $('#btn-addproduct').prop('disabled', false);
            }
        });

  }); */

  $("#add-newproduct").on("click", function(){
    var vendor_id = $('#vendor_id').val();
    if(vendor_id !== ''){
      return true;
    }else{
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // htmlerror =  htmlerror.replace("##MSG##", 'Please select vendor!');
      // $('#errormsg').html(htmlerror);
     showDangerToast('Please select vendor!');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return false;
    }
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

  $('body').on('click', '.edit-permnent', function () {
    var vendor_id = $(this).attr('data-id');
    var group = $(this).attr('data-group');
    var $this = $(this);
    if(id !== '' && group != ''){
        $.ajax({
          url: "ajax.php",
          data: {'vendor_id': vendor_id, 'group': group,'action': 'getDataEditByvendor'},            
          dataType: "json",
          type: "POST",
          success: function (data) {
            if(data.status == true){
              $('#tbody-tmp').empty();
              var html = $('#addproduct-tr-html').html();
              html = html.replace("<table>", "");
              html = html.replace("</table>", "");
              html = html.replace("<tbody>", "");
              html = html.replace("</tbody>", "");

              $.each(data.result.data, function (key, val) {
                var tmphtml = html;
                var randomnumber = Math.floor((Math.random()*1000) + 1);
                tmphtml = tmphtml.replace(/##DATAID##/g, randomnumber);
                tmphtml = tmphtml.replace(/##EDITID##/g, val.id);
                tmphtml = tmphtml.replace(/##VENDORNAME##/g, val.vendor_name);
                tmphtml = tmphtml.replace(/##VENDORID##/g, val.vendor_id);
                tmphtml = tmphtml.replace(/##STATECODE##/g, val.state);
                tmphtml = tmphtml.replace(/##PRODUCTNAME##/g, val.product_name);
                tmphtml = tmphtml.replace(/##PRODUCTID##/g, val.product_id);
                tmphtml = tmphtml.replace(/##MRP##/g, val.purchase_price);
                tmphtml = tmphtml.replace(/##GST##/g, val.gst);
                tmphtml = tmphtml.replace(/##UNIT##/g, val.unit);
                tmphtml = tmphtml.replace(/##QTY##/g, val.qty);
                tmphtml = tmphtml.replace(/##GENERICNAME##/g, val.generic_name);
                tmphtml = tmphtml.replace(/##MANUFACTURERNAME##/g, val.mfg_company);
                $('#tbody-tmp').append(tmphtml);
                // $('#tbody-tmp tr:last').find('.delete-temp').hide();
              });
              $('#add-byvendor-form').find('#day').val((typeof data.result.reminder_day !== 'undefined') ? data.result.reminder_day : '');

              $('#tmpdata-div').show();

              $("html, body").animate({ scrollTop: 0 }, "slow");
              // $($this).closest('tr').find('.delete-permnent').hide();
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