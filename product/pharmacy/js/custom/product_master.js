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
     
	$( "#company_code" ).autocomplete({
      source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query, 'action': 'getCompanyCode'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                  $(".empty-message").empty();
                    result($.map(data.result, function (item) {
                      return {
                          label: item.code,
                          value: item.id,
                          name: item.name,
                      }
                  }));
                }else{
                    $(".empty-message").text("No results found");
                }
              }
          });
        },
        focus: function( query, result ) {
          $( "#company_code" ).val( result.item.label );
          return false;
        },
        select: function( query, result ) {
            return false;
        }
    });
    
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
                          generic_name : item.generic_name,
                          schedule_cat: item.schedule_cat,
                          product_type: item.product_type,
                          product_cat: item.product_cat,
                          sub_cat: item.sub_cat,
                          batch_no : item.batch_no,
                          hsn_code : item.hsn_code,
                          //serial_no: item.serial_no,
                          opening_qty : item.opening_qty,
                          opening_qty_godown: item.opening_qty_godown,
                          mrp: item.mrp,
                          give_mrp : item.give_mrp,
                          inward_rate: item.inward_rate,
                          rack_no : item.rack_no,
                          gst_id: item.gst_id,
                          igst : item.igst,
                          cgst : item.cgst,
                          sgst : item.sgst,
                          self_no : item.self_no,
                          box_no : item.box_no,
                          company_code1: item.company_code1,
                          min_qty: item.min_qty,
                          max_qty: item.max_qty,
                          ratio : item.ratio,
                          unit: item.unit,
                          discount: item.discount,
                          discount_per: item.discount_per
                      }
                  }));
                }else{
                    // showDangerToast('Somthing Want Wrong! Try again.');
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
            $('#generic_name').val(result.item.generic_name);
            $('#hsn_code').val(result.item.hsn_code);
            //$('#serial_no').val(result.item.serial_no);
            $('#schedule_cat').val(result.item.schedule_cat).trigger('change');
            $('#product_type').val(result.item.product_type).trigger('change');
            $('#product_cat').val(result.item.product_cat).trigger('change');
            $('#sub_cat').val(result.item.sub_cat).trigger('change');
            $('#batch_no').val(result.item.batch_no);
            $('#opening_qty').val(result.item.opening_qty);
            $('#opening_qty_godown').val(result.item.opening_qty_godown);
            $('#mrp').val(result.item.mrp);
            $('#give_mrp').val(result.item.give_mrp);
            $('#rack_no').val(result.item.rack_no);
            $('#inward_rate').val(result.item.inward_rate);
            $('#gst_id').val(result.item.gst_name).trigger('change');
            $('#max_qty').val(result.item.max_qty);
            $('#self_no').val(result.item.self_no);
            $('#box_no').val(result.item.box_no);
            $('#min_qty').val(result.item.min_qty);
            $('#ratio').val(result.item.ratio);
            $('#company_code1').val(result.item.company_code1).trigger('change');
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
                // $('#company_code').val(dataarr.code);
                
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
              if(data.status === true){
                $('#btn-addcompany').html('Save');
                $('#btn-addcompany').prop('disabled', false);
                $('#add-gst-form')[0].reset();
                
                $('#gst_id').append($('<option>', { 
                    value: data.result,
                    text : dataarr.gst_name 
                }));
                $('#gst_id').val(data.result).trigger('change');
                $('#addgst-model').modal('hide');
                showSuccessToast(data.message);
              }else{
                $('#btn-addgst').html('Save');
                $('#btn-addgst').prop('disabled', false);
                showSuccessToast(data.message);
              }
            },
            error: function () {
              $('#btn-addgst').html('Save');
              $('#btn-addgst').prop('disabled', false);
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
                $('#btn-addcompany').html('Save');
                $('#btn-addcompany').prop('disabled', false);
                $('#add-unit-form')[0].reset();
                
                $('#unit').append($('<option>', { 
                    value: data.result,
                    text : dataarr.name 
                }));
                $('#unit').val(data.result).trigger('change');
                $('#addunit-model').modal('hide');
                showSuccessToast(data.message);
                
              }else{
                $('#btn-addunit').html('Save');
                $('#btn-addunit').prop('disabled', false);
                showSuccessToast(data.message);
              }
            },
            error: function () {
              $('#btn-addunit').html('Save');
              $('#btn-addunit').prop('disabled', false);
              showDangerToast('Somthing Want Wrong! Try again.');
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
	
	$('body').on('change keyup focusout', '.inward_rate,.opening_qty', function() {
        var inward_rate = $('.inward_rate').val();
        var opening_qty = $('.opening_qty').val();
        if(inward_rate !== ''&& inward_rate !== NaN && inward_rate !== "undifined" && opening_qty !=='' && opening_qty !== NaN && opening_qty !== "undifined"){
    
          inward_rate = (typeof inward_rate !== "undifined" && inward_rate !== '' && inward_rate !== NaN) ? inward_rate : 0;
          opening_qty = (typeof opening_qty !== "undifined" && opening_qty !== '' && opening_qty !== NaN) ? opening_qty : 0;
          var total = (parseInt(inward_rate)*parseInt(opening_qty));
    
        }else{
          var total ="0";
        }
        //$('.opening_stock').val(parseFloat(total).toFixed(2));
        $('.opening_stock').val(parseFloat(total).toFixed(2));
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
    
    
    
    $("#add-producttype-form").on("submit", function(event){
    event.preventDefault();
      var data = $(this).serialize();
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
      $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addproducttype', 'data': dataarr},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addproducttype').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addproducttype').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                $('#btn-addproducttype').html('Save');
                $('#btn-addproducttype').prop('disabled', false);
                $('#add-producttype-form')[0].reset();
                // $('#company_code').val(dataarr.code);
                
                $('#product_type').append($('<option>', { 
                    value: data.result,
                    text : dataarr.name 
                }));
                $('#product_type').val(data.result).trigger('change');
                $('#addproducttype-model').modal('hide');
                showSuccessToast(data.message);
                
              }else{
                $('#btn-addproducttype').html('Save');
                $('#btn-addproducttype').prop('disabled', false);
                showSuccessToast(data.message);
              }
            },
            error: function () {
              $('#btn-addproducttype').html('Save');
              $('#btn-addproducttype').prop('disabled', false);
              showDangerToast('Somthing Want Wrong! Try again.');
            }
        });
    });  
    
    
    $("#add-productcategory-form").on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
      var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
      $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addproductcategory', 'data': dataarr},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addproductcategory').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addproductcategory').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                $('#btn-addproductcategory').html('Save');
                $('#btn-addproductcategory').prop('disabled', false);
                $('#add-productcategory-form')[0].reset();
                // $('#company_code').val(dataarr.code);
                
                $('#product_cat').append($('<option>', { 
                    value: data.result,
                    text : dataarr.name 
                }));
                $('#product_cat').val(data.result).trigger('change');
                $('#addproductcategory-model').modal('hide');
                showSuccessToast(data.message);
                
              }else{
                $('#btn-addproductcategory').html('Save');
                $('#btn-addproductcategory').prop('disabled', false);
                showSuccessToast(data.message);
              }
            },
            error: function () {
              $('#btn-addproductcategory').html('Save');
              $('#btn-addproductcategory').prop('disabled', false);
              showDangerToast('Somthing Want Wrong! Try again.');
            }
        });
    }); 