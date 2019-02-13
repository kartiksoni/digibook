$(document).ready(function(){  
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


    $("#customer_city").change(function(){
    var customer_city = $(this).val();
    if(customer_city !== ''){
        $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'customer_city':customer_city, 'action':'getcustomer'},
          dataType: "json",
          success: function (data) {
              if(data.status == true){
                  $('#customer_name').children('option:not(:first)').remove();
                  $.each(data.result, function (i, item) {
                    $('#customer_name').append($('<option>', { 
                        value: item.id,
                        text : item.name 
                    }));
                });
              }else{
                  $('#customer_name').children('option:not(:first)').remove();
              }
          },
          error: function () {
              $('#customer_name').children('option:not(:first)').remove();
          }
          });
    }else{
        $('#customer_name').children('option:not(:first)').remove();
}
});

    $(".product_by").click(function(){
      var product_by = $(this).val();

      if(product_by == 'single_product'){
        $("#company_name").hide();
        $("#product_name").show();
        $("#mrp").show();
        $(".company_name").val('');
      }else if(product_by == 'company_wise'){
        $("#company_name").show();
        $("#product_name").hide();
        $("#mrp").hide();
        $(".product-name").val('');
        $(".mrp").val('');
      }else{
        $("#company_name").hide();
        $("#product_name").hide();
        $("#mrp").hide();
        $(".company_name").val('');
      }
    });

    /*$(".product-name").autocomplete({
      source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query.term, 'action': 'searchProduct'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                  $(".product-error").empty();
                    result($.map(data.result, function (item) {
                      return {
                          label: item.product_name,
                          id: item.id,
                          product_name: item.product_name,
                          mfg_company: item.mfg_company,
                          generic_name : item.generic_name,
                          schedule_cat: item.schedule_cat,
                          product_type: item.product_type,
                          product_cat: item.product_cat,
                          sub_cat: item.sub_cat,
                          batch_no : item.batch_no,
                          hsn_code : item.hsn_code,
                          serial_no: item.serial_no,
                          opening_qty : item.opening_qty,
                          opening_qty_godown: item.opening_qty_godown,
                          mrp: item.mrp
                          give_mrp : item.give_mrp,
                          inward_rate: item.inward_rate,
                          rack_no : item.rack_no,
                          gst_id: item.gst_id,
                          igst : item.igst,
                          cgst : item.cgst,
                          sgst : item.sgst,
                          self_no : item.self_no,
                          box_no : item.box_no,
                          company_code: item.company_code,
                          min_qty: item.min_qty,
                          max_qty: item.max_qty,
                          ratio : item.ratio,
                          unit: item.unit,
                          discount: item.discount,
                          discount_per: item.discount_per
                      }
                  }));
                }else{
                  $(".product-error").text("Product Not Found!");
                }
              }
          });
        },
        focus: function( query, result ) {
          $(this).val( result.item.label );
          return false;
        },
        select: function( query, result ) {
            $('#popup_mfg_company').val(result.item.mfg_company);
            $('#popup_generic_name').val(result.item.generic_name);
            $('#popup_hsn_code').val(result.item.hsn_code);
            $('#serial_no').val(result.item.serial_no);
            $('#popup_schedule_cat').val(result.item.schedule_cat).trigger('change');
            $('#popup_product_type').val(result.item.product_type).trigger('change');
            $('#popup_product_cat').val(result.item.product_cat).trigger('change');
            $('#popup_sub_cat').val(result.item.sub_cat).trigger('change');
            $('#popup_batch_no').val(result.item.batch_no);
            $('#popup_opening_qty').val(result.item.opening_qty);
            $('#popup_opening_qty_godown').val(result.item.opening_qty_godown);
            $('#product_id').val(result.item.id);
            $('.mrp').val(result.item.mrp);
            $('#popup_give_mrp').val(result.item.give_mrp);
            $('#popup_rack_no').val(result.item.rack_no);
            $('#popup_inward_rate').val(result.item.inward_rate);
            $('#popup_gst_id').val(result.item.gst_id).trigger('change');
            $('#popup_max_qty').val(result.item.max_qty);
            $('#popup_self_no').val(result.item.self_no);
            $('#popup_box_no').val(result.item.box_no);
            $('#popup_min_qty').val(result.item.min_qty);
            $('#popup_ratio').val(result.item.ratio);
            $('#popup_company_code').val(result.item.company_code).trigger('change');
            $('#popup_unit').val(result.item.unit).trigger('change');
            $("input[name=discount][value='"+result.item.discount+"']").prop("checked",true);
            if(result.item.discount == 1){
                $('#per').show();
                $('#popup_discount_per').val(result.item.discount_per);
            } 
            
            return false;
        }
    });*/
    
    $('body').on('keyup', '.mrp', function(){
    //$( ".mrp" ).keyup(function() {
      var mrp = $(this).val();
      //mrp = (typeof mrp !== 'undefined' && !isNaN(mrp) && mrp !== '') ? mrp : 0;
      getProduct(mrp);
    });

    function getProduct(mrp = null){
    $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'mrp':mrp, 'action':'getMrpWiseProduct'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                $('.product-name').children('option:not(:first)').remove();
                $.each(data.result, function (i, item) {
                $('.product-name').append($('<option>', { 
                    value: item.product_name,
                    text : item.product_name 
                }));
              });
              }else{
                $('.product-name').children('option:not(:first)').remove();
              }
            },
            error: function () {
                $('.product-name').children('option:not(:first)').remove();
            }
        });
    }
  });