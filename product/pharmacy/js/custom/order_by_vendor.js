$( document ).ready(function() {

  // table listing
  var table = $('.datatable').DataTable( {
        "ajax": "ajax.php?action=getOrder&type=1",
        "columns": [
            { "data": "no" },
            { "data": "vendor_name" },
            { "data": "product_name" },
            { "data": "purchase_price" },
            { "data": "gst" },
            { "data": "unit" },
            { "data": "qty" },
            { "data" : "id",
                "render": function (data)
                {
                    return '<button data-id='+data+' class="btn btn-primary p-2 delete-permnent"><i class="icon-trash mr-0"></i></button>';
                }

            }

        ],
        //"order": [[1, 'asc']]
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
  });

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
        }else{
          $('#statecode').val('');
          $('#vendor_name').val('');
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

            if(statecode == 24){
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
      if(data.length){
        // reset form data
        $("#vendor_id").val('').trigger('change');
        $("#selectsearch").val('product').trigger('change');
        $('#generic-name-input').val(null);
        $('#menufacturer-name-input').val(null);
        $('#editid').val(null);
        $('#add_byvendor_temp')[0].reset();

        var post = [];
        $.each(data, function (key, val) {
            post[val.name] = val.value;
        });
        
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
        
        console.log(html);
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
      $('#btn-addtop').prop('disabled', true);
      }else{
        return false;
      }

  });
  
  $('body').on('click', '.delete-temp', function () {
    $(this).closest ('tr').remove ();
    var rowCount = $('#tbody-tmp tr').length;
    if(rowCount == 0){
      $('#tmpdata-div').hide();
    }
  });

  $('body').on('click', '.edit-temp', function () {

    var dataid = $(this).closest ('tr').attr('id');
    var vendorname = $(this).closest ('tr').find('.vendor_name').val();
    var vendorid = $(this).closest ('tr').find('.vendor_id').val();
    var unit = $(this).closest ('tr').find('.unit').val();
    var statecode = $(this).closest ('tr').find('.state_code').val();
    var qty = $(this).closest ('tr').find('.qty').val();
    var purchase_price = $(this).closest ('tr').find('.purchase_price').val();
    var productname = $(this).closest ('tr').find('.product_name').val();
    var productid = $(this).closest ('tr').find('.product_id').val();
    var gst = $(this).closest ('tr').find('.gst').val();
    var generic_name = $(this).closest ('tr').find('.generic_name').val();
    var menufacturer_name = $(this).closest ('tr').find('.menufacturer_name').val();


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
    $('#add_byvendor_temp').find('#product_id').val(productid);
    $('#add_byvendor_temp').find('#gst').val(gst);
    $('#add_byvendor_temp').find('#generic-name-input').val(generic_name);
    $('#add_byvendor_temp').find('#menufacturer-name-input').val(menufacturer_name);

    $('#add_byvendor_temp').find('#generic-name').html(generic_name);
    $('#add_byvendor_temp').find('#menufacturer-name').html(menufacturer_name);

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
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

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
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('.btn-savebyvendor').html('Save');
                $('.btn-savebyvendor').prop('disabled', false);
                $('#tbody-tmp').empty();
                $('#tmpdata-div').hide();
                table.ajax.reload();
              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('.btn-savebyvendor').html('Save');
                $('.btn-savebyvendor').prop('disabled', false);
              }
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('.btn-savebyvendor').html('Save');
              $('.btn-savebyvendor').prop('disabled', false);
            }
        });
  });
  
  
  $('body').on('click', '.delete-permnent', function () {
    var id = $(this).attr('data-id');
    var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    var conf = confirm('Are you sure want to delete this record?');
    if(conf && id !== ''){
      $.ajax({
          url: "ajax.php",
          data: {'id': id, 'action': 'deleteOrder'},            
          dataType: "json",
          type: "POST",
          success: function (data) {
            if(data.status == true){
              htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
              $('#errormsg').html(htmlsuccess);
              $("html, body").animate({ scrollTop: 0 }, "slow");
              table.ajax.reload();
            }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
          },
          error: function () {
            htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
            $('#errormsg').html(htmlerror);
            $("html, body").animate({ scrollTop: 0 }, "slow");
          }
      });
    }else{
      return false;
    }
        
  });

  // SAVE DB TO ADD NEW PRODUCT
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
                $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addproductmodel').modal('toggle');
                $('#add-product')[0].reset();

                $('#product_id').val(data.result);
                $('#search').val(dataarr.product_name);
                $('#product_name').val(dataarr.product_name);
                $('#purchase_price').val(dataarr.mrp);
                $('#unit').val(dataarr.unit);
                $('#generic-name').html(dataarr.generic_name);
                $('#generic-name-input').val(dataarr.generic_name);
                $('#menufacturer-name').html(dataarr.mfg_company);
                $('#menufacturer-name-input').val(dataarr.mfg_company);

                var gst = 0;
                  if($('#statecode').val() == 24){
                    gst = parseFloat(dataarr.cgst)+parseFloat(dataarr.sgst);
                  }else{
                    gst = parseFloat(dataarr.igst);
                  }
                $('#gst').val(gst);

                $('#btn-addtop').prop('disabled', false);
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
              $('#addvendor-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addproduct').html('Save');
              $('#btn-addproduct').prop('disabled', false);
            }
        });

  });

  $("#add-newproduct").on("click", function(){
    var vendor_id = $('#vendor_id').val();
    if(vendor_id !== ''){
      return true;
    }else{
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      htmlerror =  htmlerror.replace("##MSG##", 'Please select vendor!');
      $('#errormsg').html(htmlerror);
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return false;
    }
  });

});