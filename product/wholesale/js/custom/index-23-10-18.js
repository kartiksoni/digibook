$( document ).ready(function() {

	/*---------VENDOR ORDER NOTIFICATION START-----------------*/
  
		$('.btn-cancel-v-order').click(function(){
			var $this = $(this);
	    	var id = $(this).attr('data-id');

	    	var answer = confirm('Are you sure want to cancel?');
	    	if(answer){
		    	$.ajax({
		            type: "POST",
		            url: 'ajax.php',
		            data: {'id':id, 'action':'cancelOrder'},
		            dataType: "json",
		            beforeSend: function() {
		            	$($this).prop('disabled', true);
		            	$($this).html('<i class="fa fa-spin fa-refresh"></i>');
				    },
		            success: function (data) {
		              if(data.status == true){
		              	$($this).closest('tr').find('.order-noti-error').removeClass('text-danger').addClass('text-success');
		              	$($this).closest('tr').find('.order-noti-error').html('Order cancel Success.');
		                setInterval(function() {
		                	$($this).html('Cancel');
		                	$($this).closest('tr').fadeOut('slow');
		                }, 1000);

		              }else{
		              	$($this).closest('tr').find('.order-noti-error').removeClass('text-success').addClass('text-danger');
		              	$($this).closest('tr').find('.order-noti-error').html('Order cancel fail!');
		                $($this).html('Cancel');
		              }
		              $($this).prop('disabled', false);
		            },
		            error: function () {
		            	$($this).closest('tr').find('.order-noti-error').removeClass('text-success').addClass('text-danger');
		            	$($this).closest('tr').find('.order-noti-error').html('Order cancel fail!');
		            	$($this).html('Cancel');
		            	$($this).prop('disabled', false);
		            }
		        });
		    }else{
		    	return false;
		    }

		});

		$('.btn-resend-v-order').click(function(){
	    	var $this = $(this);
	    	var id = $(this).attr('data-id');

	    	var answer = confirm('Are you sure want to resend?');
	    	if(answer){
	    		$.ajax({
		            type: "POST",
		            url: 'ajax.php',
		            data: {'id':id, 'action':'resendorder'},
		            dataType: "json",
		            beforeSend: function() {
		            	$($this).html('<i class="fa fa-spin fa-refresh"></i>');
		            	$($this).prop('disabled', true);
				    },
		            success: function (data) {
		              if(data.status == true){
		              	$($this).closest('tr').find('.order-noti-error').removeClass('text-danger').addClass('text-success');
		              	$($this).closest('tr').find('.order-noti-error').html('Order resend Success.');
		                setInterval(function() {
		                	$($this).html('Resend');
		                	$($this).closest('tr').fadeOut('slow');
		                }, 1000);

		              }else{
		              	$($this).closest('tr').find('.order-noti-error').removeClass('text-success').addClass('text-danger');
		              	$($this).closest('tr').find('.order-noti-error').html('Order resend fail!');
		                $($this).html('Resend');
		              }
		              $($this).prop('disabled', false);
		            },
		            error: function () {
		            	$($this).closest('tr').find('.order-noti-error').removeClass('text-success').addClass('text-danger');
		            	$($this).closest('tr').find('.order-noti-error').html('Order resend fail!');
		            	$($this).html('Resend');
		            	$($this).prop('disabled', false);
		            }
		        });
	    	}else{
	    		return false;
	    	}
		});

	/*---------VENDOR ORDER NOTIFICATION START-----------------*/
	
	/*---------CUSTOMER ORDER NOTIFICATION START-----------------*/
	    $('.btn-cancel-c-order').click(function(){
			var $this = $(this);
	    	var id = $(this).attr('data-id');

	    	var answer = confirm('Are you sure want to cancel?');
	    	if(answer){
		    	$.ajax({
		            type: "POST",
		            url: 'ajax.php',
		            data: {'id':id, 'action':'cancelSaleOrder'},
		            dataType: "json",
		            beforeSend: function() {
		            	$($this).prop('disabled', true);
		            	$($this).html('<i class="fa fa-spin fa-refresh"></i>');
				    },
		            success: function (data) {
		              if(data.status == true){
		              	$($this).closest('tr').find('.order-noti-error').removeClass('text-danger').addClass('text-success');
		              	$($this).closest('tr').find('.order-noti-error').html('Order cancel Success.');
		                setInterval(function() {
		                	$($this).html('Cancel');
		                	$($this).closest('tr').fadeOut('slow');
		                }, 1000);

		              }else{
		              	$($this).closest('tr').find('.order-noti-error').removeClass('text-success').addClass('text-danger');
		              	$($this).closest('tr').find('.order-noti-error').html('Order cancel fail!');
		                $($this).html('Cancel');
		              }
		              $($this).prop('disabled', false);
		            },
		            error: function () {
		            	$($this).closest('tr').find('.order-noti-error').removeClass('text-success').addClass('text-danger');
		            	$($this).closest('tr').find('.order-noti-error').html('Order cancel fail!');
		            	$($this).html('Cancel');
		            	$($this).prop('disabled', false);
		            }
		        });
		    }else{
		    	return false;
		    }

		});
		
		$('.btn-resend-c-order').click(function(){
	    	var $this = $(this);
	    	var id = $(this).attr('data-id');

	    	var answer = confirm('Are you sure want to resend?');
	    	if(answer){
	    		$.ajax({
		            type: "POST",
		            url: 'ajax.php',
		            data: {'id':id, 'action':'resendSaleorder'},
		            dataType: "json",
		            beforeSend: function() {
		            	$($this).html('<i class="fa fa-spin fa-refresh"></i>');
		            	$($this).prop('disabled', true);
				    },
		            success: function (data) {
		              if(data.status == true){
		              	$($this).closest('tr').find('.order-noti-error').removeClass('text-danger').addClass('text-success');
		              	$($this).closest('tr').find('.order-noti-error').html('Order resend Success.');
		                setInterval(function() {
		                	$($this).html('Resend');
		                	$($this).closest('tr').fadeOut('slow');
		                }, 1000);

		              }else{
		              	$($this).closest('tr').find('.order-noti-error').removeClass('text-success').addClass('text-danger');
		              	$($this).closest('tr').find('.order-noti-error').html('Order resend fail!');
		                $($this).html('Resend');
		              }
		              $($this).prop('disabled', false);
		            },
		            error: function () {
		            	$($this).closest('tr').find('.order-noti-error').removeClass('text-success').addClass('text-danger');
		            	$($this).closest('tr').find('.order-noti-error').html('Order resend fail!');
		            	$($this).html('Resend');
		            	$($this).prop('disabled', false);
		            }
		        });
	    	}else{
	    		return false;
	    	}
		});
    /*---------CUSTOMER ORDER NOTIFICATION END-----------------*/
});