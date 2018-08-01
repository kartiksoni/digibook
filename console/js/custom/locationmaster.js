$( document ).ready(function() {
    
    $("#addcity-country").change(function(){
	    var country_id = $(this).val();
	    if(country_id !== ''){
			$.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'country_id':country_id, 'action':'getCountryByState'},
              dataType: "json",
              success: function (data) {
              	if(data.status == true){
              		$('#addcity-state').children('option:not(:first)').remove();
              		$.each(data.result, function (i, item) {
					    $('#addcity-state').append($('<option>', { 
					        value: item.id,
					        text : item.name 
					    }));
					});
              	}else{
              		$('#addcity-state').children('option:not(:first)').remove();
              	}
              },
              error: function () {
              	$('#addcity-state').children('option:not(:first)').remove();
              }
          	});
		}else{
			$('#addcity-state').children('option:not(:first)').remove();
		}
	});
});