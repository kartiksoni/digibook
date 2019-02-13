$( document ).ready(function() {
	$('body').on('change', '#iteam_code_id', function(event) {
	    var id = $(this).val();
	    var code = $("#iteam_code_id option:selected").attr('data-code');
	    var name = $("#iteam_code_id option:selected").attr('data-name');
	    $("#code-company").val(code);
	    $("#name-product").val(name);
	});
});