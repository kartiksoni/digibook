$( document ).ready(function() {
    $("#freetrial-form").on("submit", function(event){
    	event.preventDefault();
    	$.ajax({
		      type: "POST",
		      url: 'ajax/freetrial.php',
		      data: $(this).serialize(),
		      dataType: "json",
		      beforeSend: function() {
		      	$('#btn-submit').prop('disabled', true);
		      	$('.loader').show();
		      },
		      success: function (data) {
		      	if(data.status == true){
		      		$('#btn-submit').prop('disabled', false);
		      		$('.loader').hide();
		      		$('#freetrial-form').html('<h4>Thank you for subscription!</h4>').fadeTo(300, 1);
		      	}else{
		      		$('#errormsg').html('<span style="color:#F45B31;font-size: 15px;">'+data.message+'</span>');
		      		$('#btn-submit').prop('disabled', false);
                	$('.loader').hide();
		      	}
		      },
              error: function () {
                $('#errormsg').html('<span style="color:#F45B31;font-size: 15px;">Somthing Want wrong! Please try again.</span>');
                $('#btn-submit').prop('disabled', false);
                $('.loader').hide();
              }
          });


	 });

});