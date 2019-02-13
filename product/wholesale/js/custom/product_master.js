$( document ).ready(function() {
    
    var radio = $('input[name=discount]:checked').val();

   if(radio == 1){
    $('#per').show();
       $('#discount_per').show();
       $("#discount_per").prop('required',true);
     }
     if(radio == 0){
      $('#per').hide();
       $("#discount_per").removeAttr('required');
       $('#discount_per').val('');
     }
     
	$( "#product_name" ).autocomplete({
      source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query.term, 'action': 'searchProduct'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                //   $(".product-error").empty();
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
                    // $(".product-error").text("Product Not Found!");
                }
              }
          });
        },
        focus: function( query, result ) {
          $(this).val( result.item.label );
          return false;
        },
        select: function( query, result ) {
            $('#mfg_company').val(result.item.mfg_company);
            $('#hsn_code').val(result.item.hsn_code);
            $('#serial_no').val(result.item.serial_no);
            $('#opening_qty_godown').val(result.item.opening_qty_godown);
            // $('#mrp').val(result.item.mrp);
            // $('#inward_rate').val(result.item.inward_rate);
            $('#gst_id').val(result.item.gst_id).trigger('change');
            $('#max_qty').val(result.item.max_qty);
            $('#min_qty').val(result.item.min_qty);
            $('#company_code1').val(result.item.company_code).trigger('change');
            $('#unit').val(result.item.unit);
            $("input[name=discount][value='"+result.item.discount+"']").prop("checked",true);
            if(result.item.discount == 1){
                $('#per').show();
                $('#discount_per').val(result.item.discount_per);
            }
            
            return false;
        }
    });


    $("#add-company-form").on("submit", function(event){
		  event.preventDefault();
	    var data = $(this).serialize();
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
                $('#btn-addcompany').html('Save');
                $('#btn-addcompany').prop('disabled', false);
                $('#add-company-form')[0].reset();
                
                $('#company_code1').append($('<option>', { 
                    value: data.result,
                    text : dataarr.name 
                }));
                $('#company_code1').val(data.result).trigger('change');
                $('#addcompany-model').modal('hide');
                showSuccessToast(data.message);
              }else{
                $('#btn-addcompany').html('Save');
                $('#btn-addcompany').prop('disabled', false);
                showDangerToast(data.message);
              }
            },
            error: function () {
              $('#btn-addcompany').html('Save');
              $('#btn-addcompany').prop('disabled', false);
              showDangerToast('Somthing Want Wrong! Try again.');
            }
        });

	});
	
    $("#add-unit-form").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
      $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addunit', 'data': dataarr},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addunit').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addunit').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                $('#btn-addunit').html('Save');
                $('#btn-addunit').prop('disabled', false);
                $('#add-unit-form')[0].reset();

                
                $('#unit').append($('<option>', { 
                    value: data.result,
                    text : dataarr.name 
                }));
                $('#unit').val(data.result);
                $('#addunit-model').modal('hide');
                showSuccessToast(data.message);
              }else{
                $('#btn-addunit').html('Save');
                $('#btn-addunit').prop('disabled', false);
                showDangerToast(data.message);
              }
            },
            error: function () {
              $('#btn-addunit').html('Save');
              $('#btn-addunit').prop('disabled', false);
              showDangerToast('Somthing Want Wrong! Try again.');
            }
        });
    });
	
	$("#add-gst-form").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addgst', 'data': dataarr},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addgst').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addgst').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                $('#btn-addgst').html('Save');
                $('#btn-addgst').prop('disabled', false);
                $('#add-gst-form')[0].reset();
                
                $('#gst_id').append($('<option>', { 
                    value: data.result,
                    text : dataarr.gst_name 
                }));
                $('#gst_id').val(data.result).trigger('change');
                
                $('#addcompany-model').modal('hide');
                showSuccessToast(data.message);
              }else{
                
                $('#btn-addgst').html('Save');
                $('#btn-addgst').prop('disabled', false);
                showDangerToast(data.message);
              }
            },
            error: function () {
              $('#btn-addgst').html('Save');
              $('#btn-addgst').prop('disabled', false);
              showDangerToast('Somthing Want Wrong!');
            }
        });
  });

	
	$('body').on('change keyup focusout', '.inward_rate,.opening_qty', function() {
        var inward_rate = $('.inward_rate').val();
        inward_rate = (typeof inward_rate !== 'undefined' && !isNaN(inward_rate) && inward_rate !== '') ? parseFloat(inward_rate) : 0;
        var opening_qty = $('.opening_qty').val();
        opening_qty = (typeof opening_qty !== 'undefined' && !isNaN(opening_qty) && opening_qty !== '') ? parseFloat(opening_qty) : 0;
        $('.opening_stock').val((inward_rate*opening_qty).toFixed(2));
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


       $('#per').hide();
       $("#discount_per").removeAttr('required');
      
    function dis_yes() 
    {
      $('#per').show();
       $('#discount_per').show();
       $("#discount_per").prop('required',true);
    }
     function dis_no() 
     {
       $('#per').hide();
       $("#discount_per").removeAttr('required');
       $('#discount_per').val('');
     }
     
     
    /*$('body').on('change keyup focusout', '.igst,.sgst,.cgst', function() {
        var igst = $(".igst").val();
        var sgst = $(".sgst").val();
        var cgst = $(".cgst").val();
        
        if(igst !=''){
            sgst = igst / 2;
            cgst = igst / 2;
            $(".sgst").val(sgst);
            $(".cgst").val(cgst);
        }else if(sgst != ''){
            $(".cgst").val(sgst);
            igst = sgst * 2;
            $(".igst").val(igst);
        }else if(cgst != ''){
            $(".sgst").val(cgst);
            igst = cgst * 2;
            $(".igst").val(igst);
        }
    });*/
    $('body').on('change', '.gst_id', function() {
        var gst_id = $(this).val();
        $(".igst").val("");
        $(".sgst").val("");
        $(".cgst").val("");
        $(".gst_show").show();
        if(gst_id == '1' || gst_id == '2' || gst_id =='3'){
            $(".gst_show").hide();
        }else if(gst_id != ''){
          $.ajax({
              url: "ajax.php",
              data: {'gst_id': gst_id, 'action': 'getGST'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                    $(".igst").val(data.result.igst);
                    $(".sgst").val(data.result.sgst);
                    $(".cgst").val(data.result.cgst);
                }else{
                    
                    $(".igst").val("");
                    $(".sgst").val("");
                    $(".cgst").val("");
                }
              }
          });
        }else{
            $(".igst").val("");
            $(".sgst").val("");
            $(".cgst").val("");
            $(".gst_show").hide();
        }
        
    });
    
    // add by gautam
    $( "#product_name" ).autocomplete({
     	source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query,'action': 'getProductMrpGeneric'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                	$(".empty-message").empty();
	                  result($.map(data.result, function (item) {
	                    return {
	                        label: item.name,
	                        value: item.id,
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
          $('#searchid').val(result.item.value);
          return false;
        }
    });
    
    