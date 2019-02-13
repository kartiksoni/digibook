$( document ).ready(function() {

	var table = $('.table-itemdatail').DataTable();
    
    $('body').on('change', '.product_by', function () {
    	//0 = MRP AND 1 = ITEM NAME
	  	var val = $(this).val();
	  	if(val == 0){
	  		$('#mrp-div').show();
	  		$('#mrp').val(null);
	  		$('#item').children('option:not(:first)').remove();
	  	}else{
	  		$('#mrp-div').hide();
	  		getItem('', 1);
	  	}
	  	$('#detail-div').hide();
	});

	$( "#mrp" ).keyup(function() {
		var mrp = $(this).val();
		mrp = (typeof mrp !== 'undefined' && !isNaN(mrp) && mrp !== '') ? mrp : 0;
		getItem(mrp);
	});

	$('body').on('change', '#item', function () {
		var val = $(this).val();
		if(val !== ''){
			$('#detail-div').show();
			$('#item_type_div').hide();
			$('#item_detail_div').hide();
		}else{
			$('#detail-div').hide();
		}
		$('input[name="type"]').prop('checked', false);
		$('input[name="item_type"]').prop('checked', false);
	});

	$('body').on('change', '.item_type', function () {
		var val = $(this).val(); //0 = all and 1 = batch wise
		if(val == 1){
			getAllBatch();
			$('#item_detail_div').show();
		}else{
			$('#item_detail_div').hide();
			table.clear().draw();
		}
	});

	$('body').on('change', '.type', function () {
		// 0 = Item Registration In Details & 1 = Item Registration Sale Only & 2 = Item Registration Purchase Only & 3 = Item Registration Batch Wise
		var val = $(this).val();
		if(val == 1 || val == 2 || val == 3){
			$('#item_type_div').show();
		}else{
		    $('input[name="item_type"]').prop('checked', false)
			$('#item_type_div').hide();
			$('#item_detail_div').hide();
		}
	});

	// mrp : mrp value
	// product_by : 0 = mrp amount wise and 1 = all item wise 
	function getItem(mrp = 0, product_by = 0){
		$.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'mrp':mrp, 'product_by': product_by,  'action':'getProductForItemRegistration'},
            dataType: "json",
            beforeSend: function() {
                $('.item-loader').show();
            },
            success: function (data) {
              $('.item-loader').hide();
              if(data.status == true){
              	$('#item').children('option:not(:first)').remove();
              	$.each(data.result, function (i, item) {
		            $('#item').append($('<option>', { 
		                value: item.id,
		                text : item.product_name 
		            }));
	            });
              }else{
                $('#item').children('option:not(:first)').remove();
              }
            },
            error: function () {
                $('.item-loader').hide();
                $('#item').children('option:not(:first)').remove();
                return false;
            }
        });
	}

	function getAllBatch(){
		var product_id = $('#item').val();
		table.clear().draw();
		if(product_id != ''){
			$.ajax({
	            type: "POST",
	            url: 'ajax.php',
	            data: {'product_id':product_id, 'action':'getAllBatchByProduct'},
	            dataType: "json",
	            beforeSend: function() {
	                $('.batch-loader').show();
	            },
	            success: function (data) {
	              if(data.status == true){
	              	$.each(data.result, function (key, value) {
	              		var radio = '<input type="radio" name="item_id" class="item_id" value="'+value.id+'" data-parsley-errors-container="#error-item_id" data-parsley-required-message="Please select at least one batch!" required>';
	              		var srno = key+1;
	              		var itemname = value.product_name;
	              		var batchno = value.batch_no;
	              		var ex_date = value.ex_date;
	              		var currentstock = value.currentstock;
	              		table.row.add([ radio, srno, itemname, batchno, ex_date, currentstock]).draw();
	              	});
	              	$('.batch-loader').hide();
	              }else{
	                $('.batch-loader').hide();
	              }
	            },
	            error: function () {
	                $('.batch-loader').hide();
	                return false;
	            }
	        });
		}else{
			table.clear().draw();
		}
	}

});