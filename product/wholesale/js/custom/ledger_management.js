// author : Gautam Makwana
// date   : 25-07-2018
$(document).ready(function(){
      
      
   var seller =  $('#customer_role:visible').val();
       if(seller === 'Reseller'){
        $('.panno-div').show();
	    	$('.gstno-div').show();
	    	$('.bankname-div').show();
	    	$('.bankacno-div').show();
	    	$('.branchname-div').show();
	    	$('.ifsccode-div').show();
	    	$('.dlno1-div').show();
	    	$('.dlno2-div').show();
	    	$('.adharno-div').show();
	    	$('.resellerprice-div').show(); 
     }
      
      
	$("#customer_role").change(function(){
		var role = $(this).val();

		if(role == 'Reseller'){
			$('.panno-div').show();
	    	$('.gstno-div').show();
	    	$('.bankname-div').show();
	    	$('.bankacno-div').show();
	    	$('.branchname-div').show();
	    	$('.ifsccode-div').show();
	    	$('.dlno1-div').show();
	    	$('.dlno2-div').show();
	    	$('.adharno-div').show();
	    	$('.resellerprice-div').show();
		}else{
			$('.panno-div').hide();
	    	$('.gstno-div').hide();
	    	$('.bankname-div').hide();
	    	$('.bankacno-div').hide();
	    	$('.branchname-div').hide();
	    	$('.ifsccode-div').hide();
	    	$('.dlno1-div').hide();
	    	$('.dlno2-div').hide();
	    	$('.adharno-div').hide();
	    	$('.resellerprice-div').hide();
		}

	});


	// for get country wise state
	$("#country").change(function(){
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

	// for get state wise city
	$("#state").change(function(){
		var state_id = $(this).val();
		if(state_id !== ''){
			$.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'state_id':state_id, 'action':'getStateByCity'},
              dataType: "json",
              success: function (data) {
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
			$('#city').children('option:not(:first)').remove();
		}
	});

	// for get group by account type
	$("#type").change(function(){
		var type = $(this).val();

		if(type !== ''){
			$.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'type':type, 'action':'getGroupByAccountType'},
              dataType: "json",
              beforeSend: function() {
              	$('.type-loader').show();
		      },
              success: function (data) {console.log(data);
              	if(data.status == true){
              		$('#subtype').children('option:not(:first)').remove();
              		$.each(data.result, function (i, item) {
					    $('#subtype').append($('<option>', { 
					        value: item.id,
					        text : item.name 
					    }));
					});
              	}else{
              		$('#subtype').children('option:not(:first)').remove();
              	}
              	$('.type-loader').hide();
              },
              error: function () {
              	$('#subtype').children('option:not(:first)').remove();
              	$('.type-loader').hide();
              }
          	});
		}else{
			$('#subtype').children('option:not(:first)').remove();
			$('.type-loader').hide();
		}
	});
	$(".customer_type").change(function(){
	    var customer_type = $(this).val();
	    $(".gstno").removeAttr('readonly');
	    $(".gst_error").show();
	    $(".gstno").prop('required',true);
	    if(customer_type == "GST_unregistered"){
	        $(".gst_error").hide();
	        $(".gstno").attr('readonly', true);
	        $(".gstno").removeAttr('required');
	    }
	    if(customer_type == "Consumer"){
	        $(".gst_error").hide();
	        $(".gstno").attr('readonly', true);
	        $(".gstno").removeAttr('required');
	    }
	    if(customer_type == "Overseas"){
	        $(".gst_error").hide();
	        $(".gstno").attr('readonly', true);
	        $(".gstno").removeAttr('required');
	    }
	});    
});

$("#add-area-form").on("submit", function(event){
	  event.preventDefault();
    var data = $(this).serialize();
    var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
    $.ajax({
        type: "POST",
        url: 'ajax.php',
        data: {'action':'addarea', 'data': dataarr},
        dataType: "json",
        beforeSend: function() {
          $('#btn-addarea').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
          $('#btn-addarea').prop('disabled', true);
        },
        success: function (data) {
          if(data.status == true){
            $('#btn-addarea').html('Save');
            $('#btn-addarea').prop('disabled', false);
            $('#add-area-form')[0].reset();
            
            $('#Area').append($('<option>', { 
                value: data.result,
                text : dataarr.name 
            }));
            $('#Area').val(data.result).trigger('change');
            $('#addarea-model').modal('hide');
            showSuccessToast(data.message);
          }else{
            $('#btn-addarea').html('Save');
            $('#btn-addarea').prop('disabled', false);
            showDangerToast(data.message);
          }
        },
        error: function () {
          $('#btn-addarea').html('Save');
          $('#btn-addarea').prop('disabled', false);
          showDangerToast('Somthing Want Wrong! Try again.');
        }
    });

});

$("#gst_no").keyup(function(){
    var gst_value = $(this).val();
    if(gst_value != ''){
        if (gst_value.match(/^([0-9]{2}[a-zA-Z]{4}([a-zA-Z]{1}|[0-9]{1})[0-9]{4}[a-zA-Z]{1}([a-zA-Z]|[0-9]){3}){0,15}$/)) {
            $("#gst_no").removeClass("parsley-error");
            $("#gst_no").addClass("parsley-success");
        }else{
            $("#gst_no").addClass("parsley-error");  
        }
    }else{
        $("#gst_no").addClass("parsley-error");
    }
    
});