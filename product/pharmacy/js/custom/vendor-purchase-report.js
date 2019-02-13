$( document ).ready(function() {
	/*---------------CUSTOMER CITY SELECT 2 AUTO SEARCH- START---------------*/
    $("#city").select2({
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

    $("#city").change(function(){
        var city_id = $(this).val();
        if(city_id !== ''){
            getVendorByCity(city_id);
        }else{
          $('#vendor').children('option:not(:first)').remove();
        }
        // $('#vendor').trigger("change");
    });
    
    function getVendorByCity(city_id){
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'city_id':city_id, 'action':'getCityByVendor'},
            dataType: "json",
            beforeSend: function() {
                $('.vendor-loader').show();
            },
            success: function (data) {
              $('.vendor-loader').hide();
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
              $('.vendor-loader').hide();
              $('#vendor').children('option:not(:first)').remove();
            }
        });
    }

    $('input[name=type]').on('change', function() {
		var type = $('input[name=type]:checked').val();

		if(type == 'company_wise'){
			$('#company_div').show();
			$('#product_div').hide();
			$('#mrp_div').hide();
		}else if(type == 'single_product'){
			$('#company_div').hide();
			$('#product_div').show();
			$('#mrp_div').show();
		}else{
			$('#company_div').hide();
			$('#product_div').hide();
			$('#mrp_div').hide();
		}
	});

	
	$('body').on('keyup', '#mrp', function () {
		var mrp = $.trim($(this).val());
		getMrpProduct(mrp);
	});

	function getMrpProduct(mrp = null){
		$.ajax({
          	type: "POST",
          	url: 'ajax_second.php',
          	data: {'mrp':mrp, 'action':'getProductMrpWise'},
          	dataType: "json",
          	success: function (data) {console.log(data);
          		if(data.status == true){
          			$('#product').children('option:not(:first)').remove();
          			$.each(data.result, function (i, item) {
				    	$('#product').append($('<option>', { 
				        	value: item.name,
				        	text : item.name 
				    	}));
					});
          		}else{
          			$('#product').children('option:not(:first)').remove();
          		}
          	},
          	error: function () {
          		$('#product').children('option:not(:first)').remove();
          	}
      	});
	}
});