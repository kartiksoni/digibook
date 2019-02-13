$( document ).ready(function() {

	$('body').on('change', '.company_by', function () {
	  	var val = $(this).val();
	  	if(val == 0){
	  		$('.companyd').show();
	  	}else{
	  		$('.companyd').hide();
	  	}
	});
});