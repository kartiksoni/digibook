$( document ).ready(function() {
    $('#selectsearch').on('change', function() {
    	var val = $(this).val();
    	if(val == 'product'){
    		$('#search-lable').html('Enter Product Name')
    	}else if(val == 'mrp'){
    		$('#search-lable').html('Enter MRP')
    	}else{
    		$('#search-lable').html('Enter Generic Name')
    	}
    	$('#search').val(null);
	});


    // auto search product mrp and generic

    $( "#search" ).autocomplete({
     	source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query, 'type': $('#selectsearch').val(),'action': 'getProductMrpGeneric'},            
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


});