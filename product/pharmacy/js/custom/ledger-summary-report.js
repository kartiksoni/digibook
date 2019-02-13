$(document).ready(function(){

  function getparticular(city_id){

    //particular select box
    var particular_select = $("#ledger_id");

    $.ajax({
			type: "POST",
			url: 'ajax_second.php',
			data: {'city_id':city_id, 'action':'getCustomerVendorByCity'},
			dataType: "json",
			beforeSend: function() {
				particular_select.attr('disabled','disabled');
			},
			success: function (data) {
				particular_select.removeAttr('disabled');
			  	if(data.status == true){
					// alert(JSON.stringify(data.result));
				
					//append option to brand/mark select
					particular_select.children('option:not(:first)').remove();
					$.each(data.result, function (i, item) {
						particular_select.append($('<option>', { 
							value: item.id,
							text : item.name 
						}));
					});

				}else{
					particular_select.children('option:not(:first)').remove();
				}
			},
			error: function () {
			  particular_select.children('option:not(:first)').remove();
			}
		  });
  }

/**
 * Search City Name In Datbase With Not Making Request if searchterm is Empty
 * Minimum Length is 1
 */
$("#city_id").select2({
    ajax: { 
      url: "ajax_second.php",
      type: "post",
      dataType: 'json',
      delay: 250,
      data: function (params) {
       return {
         searchTerm: params.term.trim(), // search term
         action: 'getCityWithAll'
       };
      },
      processResults: function (response) {
        return {
           results: response
        };
      },
      cache: true,
      transport: function(params, success, failure) {
        // console.log(params);
        if (!params.data.searchTerm.trim().length) {
          return false;
        }
        var $request = $.ajax(params);
  
        $request.then(success);
        $request.fail(failure);
  
        return $request;
      }
   },
   minimumInputLength: 1
});
$('#city_id').on("select2:select", function() { 
  var city_id = $(this).val();
  getparticular(city_id);
});


});