$( document ).ready(function() {
    $('body').on('change', '.popup_gst_id', function () {
        var val = $(this).val();
        var igst = $(".popup_gst_id option:selected").attr('data-igst');
        var cgst = $(".popup_gst_id option:selected").attr('data-cgst');
        var sgst = $(".popup_gst_id option:selected").attr('data-sgst');
        if(val == '1' || val == '2' || val =='3'){
            $(".gstdiv").hide();
        }else if(val !== ''){
            $('.gstdiv').show();
            $('#add-product').find('.igst').val(igst);
            $('#add-product').find('.cgst').val(cgst);
            $('#add-product').find('.sgst').val(sgst);
        }else{
            $('.gstdiv').hide();
            $('#add-product').find('.igst').val(null);
            $('#add-product').find('.cgst').val(null);
            $('#add-product').find('.sgst').val(null);
        }
    });
    
    
    $('input[name=discount]').change(function(){
        var value = $( 'input[name=discount]:checked' ).val();
        if(value == 1){
            $('#per').show();
        }else{
            $('#per').hide();
            $('#discount_per').val(0)
        }
    });
    
    $("#popup_product_name").autocomplete({
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
                          value: item.id,
                          product_name: item.product_name,
                          mfg_company: item.mfg_company,
                          hsn_code : item.hsn_code,
                          serial_no: item.serial_no,
                          opening_qty_godown: item.opening_qty_godown,
                          mrp: item.mrp,
                          inward_rate: item.inward_rate,
                          gst_id: item.gst_id,
                          igst : item.igst,
                          cgst : item.cgst,
                          sgst : item.sgst,
                          company_code: item.company_code,
                          min_qty: item.min_qty,
                          max_qty: item.max_qty,
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
            $('#popup_hsn_code').val(result.item.hsn_code);
            $('#popup_serial_no').val(result.item.serial_no);
            $('#popup_opening_qty_godown').val(result.item.opening_qty_godown);
            // $('#popup_mrp').val(result.item.mrp);
            // $('#inward_rate').val(result.item.inward_rate);
            $('.popup_gst_id').val(result.item.gst_id).trigger('change');
            // $('#popup_max_qty').val(result.item.max_qty);
            // $('#popup_min_qty').val(result.item.min_qty);
            $('#company_code').val(result.item.company_code).trigger('change');
            $('#popup_unit').val(result.item.unit);
            $("input[name=discount][value='"+result.item.discount+"']").prop("checked",true);
            if(result.item.discount == 1){
                $('#per').show();
                $('#popup_discount_per').val(result.item.discount_per);
            }else{
                $('#per').hide();
                $('#popup_discount_per').val(0);
            }
            
            return false;
        }
    });
    
    $("#add-company-form").on("submit", function(event){
		event.preventDefault();
	    var data = $(this).serialize();
	    // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
	    // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
	    var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
	    $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addcompany', 'data': dataarr},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addcompany').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addcompany').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#addcompany-errormsg').html(htmlsuccess);
                //$('#btn-addcompany').html('Save');
                //$('#btn-addcompany').prop('disabled', false);
                $('#add-company-form')[0].reset();
                // $('#company_code').val(dataarr.code);
                
                $('#company_code').append($('<option>', { 
                    value: data.result,
                    text : dataarr.name+' - '+dataarr.code 
                }));
                $('#company_code').val(data.result).trigger('change');
                
                setTimeout( function(){
                	$('#btn-addcompany').html('Save');
                	$('#btn-addcompany').prop('disabled', false);
                	$('#addcompany-model').modal('hide');
                	$('#addcompany-errormsg').html(null);
  				}  , 1000 );
              }else{
                showDangerToast(data.message);
                // htmlerror =  htmlerror.replace("##MSG##", data.message);
                // $('#addcompany-errormsg').html(htmlerror);
                $('#btn-addcompany').html('Save');
                $('#btn-addcompany').prop('disabled', false);
              }
            },
            error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#addcompany-errormsg').html(htmlerror);
              $('#btn-addcompany').html('Save');
              $('#btn-addcompany').prop('disabled', false);
            }
        });

	});
	$('body').on('change keyup focusout', '.igstpop', function() {
        var igst = $(".igstpop").val();
        if(igst !==''){
            sgst = igst / 2;
            cgst = igst / 2;
            $(".sgstpop").val(sgst);
            $(".cgstpop").val(cgst);
        }else{
            $(".sgstpop").val("");
            $(".cgstpop").val("");
        }
    });


});