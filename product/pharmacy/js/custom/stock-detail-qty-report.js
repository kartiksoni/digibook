$( document ).ready(function() {
    $("#company").change(function(){
	    
	    if($(this).val() == 'company_wise'){
	    	$('#company-code-div').show();
	    }else{
	    	$('#company-code-div').hide();
	    	$("#company_code").val('').trigger('change');
	    }
	});
});