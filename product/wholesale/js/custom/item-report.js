$(document).ready(function(){

	$('input[name=type]').on('change', function() {
		var item = $('#item').val();
		var type = $('input[name=type]:checked').val();

		if(item == '' && type == 'batch_wise'){
			$("input[name=type][value='all']").prop("checked",true);
			showDangerToast('Please Select Item');
			return false;
		}else if(item != '' && type == 'batch_wise'){
			var product_name = $('#item').val();
			if(product_name != ''){
				getBatch(product_name);
			}
			$('#batch_div').show();
		}else{
			$('#batch_div').hide();
		}
	});

	$('body').on('change', '#item', function() {
	  var item = $(this).val();
	  getBatch(item);
	});

	function getBatch(product_name = null){
		if(product_name != ''){
			$.ajax({
	          	type: "POST",
	          	url: 'ajax_second.php',
	          	data: {'product_name':product_name, 'action':'getAllBatchByProductName'},
	          	dataType: "json",
	          	success: function (data) {console.log(data);
	          		if(data.status == true){
	          			$('#batch').children('option:not(:first)').remove();
	          			$.each(data.result, function (i, item) {
					    	$('#batch').append($('<option>', { 
					        	value: item.batch_no,
					        	text : item.batch_no 
					    	}));
						});
	          		}else{
	          			$('#batch').children('option:not(:first)').remove();
	          		}
	          	},
	          	error: function () {
	          		$('#batch').children('option:not(:first)').remove();
	          	}
	      	});
	    }else{
	    	$('#batch').children('option:not(:first)').remove();
	    }
	}
});