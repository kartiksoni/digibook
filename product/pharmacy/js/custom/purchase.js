// author : Kartik Champaneriya
// date   : 28-07-2018
$(document).ready(function(){
    $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.product-tr').length;//for product length
        var html = $('#html-copy').html();
        console.log(html);
        html = html.replace('##SRNO##',totalproduct);
        html = html.replace('##SRPRODUCT##',totalproduct);
        html = html.replace('<table>','');
        html = html.replace('</table>','');
        html = html.replace('<tbody>','');
        html = html.replace('</tbody>','');
        $('#product-tbody').append(html);
        $(".product-select"+totalproduct).select2();
    });

    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('tr').remove ();
        $('.f_amount').trigger("change");
    });

});








// auther : Gautam Makwana
//date    : 30-7-2018
$( document ).ready(function() {
    $("#city").change(function(){
        var city_id = $(this).val();
        if(city_id !== ''){
            $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'city_id':city_id, 'action':'getCityByVendor'},
                dataType: "json",
                success: function (data) {console.log(data);
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
                  $('#vendor').children('option:not(:first)').remove();
                }
            });
        }else{
          $('#vendor').children('option:not(:first)').remove();
        }
        $('#vendor').trigger("change");
    });
    
    
    $('#add-vendor').on('change', '#country', function() {
        var country_id = $(this).val();
        if(country_id !== ''){
          $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'country_id':country_id, 'action':'getCountryByState'},
              dataType: "json",
              success: function (data) {console.log(data);
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
        var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

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
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addvendormodel').modal('toggle');
                $('#city').trigger("change");
                $('#add-vendor')[0].reset();


                $.ajax({
                  type: "POST",
                  url: 'ajax.php',
                  data: {'action':'getAllVendorCity'},
                  dataType: "json",
                  success: function (data) {console.log(data);
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

              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#addvendor-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addvendor').html('Save');
              $('#btn-addvendor').prop('disabled', false);
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#addvendor-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addvendor').html('Save');
              $('#btn-addvendor').prop('disabled', false);
            }
        });

    });


    $("#add-product").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serialize();
        var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

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


});
    
    
    
    
    