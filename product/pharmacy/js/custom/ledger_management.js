// author : Gautam Makwana
// date   : 25-07-2018
$(document).ready(function(){

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
              },
              error: function () {
              	$('#subtype').children('option:not(:first)').remove();
              }
          	});
		}else{
			$('#subtype').children('option:not(:first)').remove();
		}
	});
});