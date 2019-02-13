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
            $('.bankaccounttype-div').show(); 
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
            $('.bankaccounttype-div').show();
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
	    	$('.bankaccounttype-div').hide();
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
              success: function (data) {
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

$('body').on('keyup paste', '#gst_no', function () {
    var gst_value = $(this).val();
    var $this = $(this);

    if(gst_value != ''){
        if (gst_value.match(/^([0-9]{2}[a-zA-Z]{4}([a-zA-Z]{1}|[0-9]{1})[0-9]{4}[a-zA-Z]{1}([a-zA-Z]|[0-9]){3}){0,15}$/)) {
            $("#gst_no").removeClass("parsley-error");
            $("#gst_no").addClass("parsley-success");
            $($this).css('width','75%');
            $('#validate-span').html('<button type="button" class="btn btn-outline-primary btn-sm pull-right" id="btn-validate-gst" style="margin-top: 30px;position: absolute;top: 0;right: 15px;">Validate</button>');
        }else{
            $("#gst_no").addClass("parsley-error");
            $($this).css('width','100%');
            $('#validate-span').empty();
        }
    }else{
        $("#gst_no").addClass("parsley-error");
        $($this).css('width','100%');
        $('#validate-span').empty();
    }
    
});


// check gst number
$('body').on('click', '#btn-validate-gst', function () {
  var gst_no = $('#gst_no').val();
  var $this = $(this);

  if(gst_no != ''){
    if (gst_no.match(/^([0-9]{2}[a-zA-Z]{4}([a-zA-Z]{1}|[0-9]{1})[0-9]{4}[a-zA-Z]{1}([a-zA-Z]|[0-9]){3}){0,15}$/)) {
      $.ajax({
          type: "POST",
          url: 'ajax_second.php',
          data: {'action':'checkgst', 'gst_no': gst_no},
          dataType: "json",
          beforeSend: function() {
            $($this).html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
            $($this).prop('disabled', true);
          },
          success: function (data) {
            $('#gst-info-model').find('#gst-info-div').html(data.message);
            $('#gst-info-model').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
            $($this).html('Validate');
            $($this).prop('disabled', false);
          },
          error: function () {
            $($this).html('Validate');
            $($this).prop('disabled', false);
            $('#gst-info-model').modal('hide');
            showDangerToast('Somthing Want Wrong! Try again.');
          }
      });
    }else{
      showDangerToast('Please enter valid GST No!');
    }
  }else{
    showDangerToast('Please enter GST No!');
  }
});


$("#ifsc_code").keyup(function(){
    var ifsc_value = $(this).val();
    if(ifsc_value != ''){
        if (ifsc_value.match(/^([A-Z]{4}0[A-Z0-9]{6})$/)) {
            $("#ifsc_code").removeClass("parsley-error");
            $("#ifsc_code").addClass("parsley-success");
        }else{
            $("#ifsc_code").addClass("parsley-error");  
        }
    }else{
        $("#ifsc_code").addClass("parsley-error");
    }
    
});


 $('body').on('change keyup', '#coname, #city',  function () {
// $("#coname").keyup(function(){

  var companyname = $('#coname').val();
  var type = $('#ledger_type').val();
  var detailid = $('#detailid').val();
  var city = $('#city').val();
  
  console.log({companyname, type, detailid, city});
  var action = "LedgerWiseComanyName"; 
   if(companyname !=''){
   $.ajax({
    type:"POST",
     url: "ajax.php",
     data : {action:action ,companyname:companyname ,type:type ,city:city ,detailid:detailid},
     success: function(data){
        if(data == "existing"){
          $("#nameget").show();
          $("#coname").addClass("parsley-error");  
          $("#submit").prop('disabled', true);
        }else if(data == "notexisting"){
            $("#coname").removeClass("parsley-error");
           $("#coname").addClass("parsley-success");
          $("#nameget").hide();
          $("#submit").prop('disabled', false);
        }
      }
   });
  } else{
       $("#nameget").hide();
       $("#coname").removeClass("parsley-error");
       $("#submit").prop('disabled', false);
  }
});