$( document ).ready(function() {

  var table = $('.datatable').DataTable( {
        "ajax": "ajax.php?action=getOrder&type=2",
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


    $( "#search" ).autocomplete({
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
    });

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
      if(statecode == 24){
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
                $('#generic_name').val(dataarr.generic_name);
                $('#mfg_co').val(dataarr.mfg_company);

                $('#sgst').val(dataarr.sgst);
                $('#cgst').val(dataarr.cgst);
                $('#igst').val(dataarr.igst);
                $('#purchase_price').val(dataarr.give_mrp);
                $('#unit').val(dataarr.unit);

                $('#btn-addtmp').prop('disabled', false);
                setGST();
                getVendorByProduct('');
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


  $("#byproduct_temp_form").on("submit", function(event){
    event.preventDefault();
        var data = $(this).serialize();
        var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
        var randomnumber = Math.floor((Math.random()*1000) + 1);
        console.log(dataarr);
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
      $('#product_id').val(null);
      $('#vendor_name').val(null);
      $('#editid').val(null);

      $('#sgst').val(null);
      $('#cgst').val(null);
      $('#igst').val(null);
      $('#statecode').val(null);

      $('#btn-addtmp').prop('disabled', true);

  });
  
  $('body').on('click', '.delete-temp', function () {
    $(this).closest('tr').remove();
    var rowCount = $('#tbody-tmp tr').length;
      if(rowCount == 0){
        $('#tmpdata-div').hide();
      }
  });

  $('body').on('click', '.edit-temp', function () {
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
    console.log('gst'+gst)

    

    

    // set value to form
    $('#byproduct_temp_form').find('#editid').val(dataid);
    $('#byproduct_temp_form').find('#search').val(product_name);
    $('#byproduct_temp_form').find('#product_id').val(product_id);
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
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

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
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('.btn-savebyproduct').html('Save');
                $('.btn-savebyproduct').prop('disabled', false);
                $('#tbody-tmp').empty();
                $('#tmpdata-div').hide();
                table.ajax.reload();
              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('.btn-savebyproduct').html('Save');
                $('.btn-savebyproduct').prop('disabled', false);
              }
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('.btn-savebyproduct').html('Save');
              $('.btn-savebyproduct').prop('disabled', false);
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


});