$( document ).ready(function() {

	var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

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
				var id = $(this).attr('data-id');
				$('#reminderid').val(id);
				$('#set-reminder-model').modal('show');
				$('#btn-reminder').addClass('save-resend-v-order').removeClass('save-resend-c-order');
			});

			
			$('body').on('click', '.save-resend-v-order', function () {
				var tmp_success = htmlsuccess;
        		var tmp_error = htmlerror;
				var day = $('#day').val();
				var id = $('#reminderid').val();

				if(day !== ''){
					$.ajax({
			            type: "POST",
			            url: 'ajax.php',
			            data: {'id':id, 'day': day, 'action':'resendorder'},
			            dataType: "json",
			            beforeSend: function() {
			            	$('#btn-reminder').html('<i class="fa fa-spin fa-refresh"></i>');
			            	$('#btn-reminder').prop('disabled', true);
					    },
			            success: function (data) {
			            	$('#btn-reminder').prop('disabled', false);
			            	$('#btn-reminder').html('Resend');
			              	if(data.status == true){
                                showSuccessToast('Reminder added success.');
			              		//tmp_success = tmp_success.replace("##MSG##", 'Reminder added success.');
                    			//$('#reminder-errormsg').html(tmp_success);
                    			$('#set-reminder-model').modal('hide');
                    			$('#TR-V-'+id).fadeOut('slow');
			              	}else{
                                 showDangerToast('Reminder added fail!');
			              		// tmp_error = tmp_error.replace("##MSG##", 'Reminder added fail!');
                    			// $('#reminder-errormsg').html(tmp_error);
                    			return false;
			              	}
			            },
			            error: function () {
			                showDangerToast('Reminder added fail!');
			            	// tmp_error = tmp_error.replace("##MSG##", 'Reminder added fail!');
                			// $('#reminder-errormsg').html(tmp_error);
                			$('#btn-reminder').prop('disabled', false);
			            	$('#btn-reminder').html('Resend');
                			return false;
			            }
			        });
				}else{
				    showDangerToast('day is required!');
					// tmp_error = tmp_error.replace("##MSG##", 'day is required!');
                    // $('#reminder-errormsg').html(tmp_error);
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
			var id = $(this).attr('data-id');
			$('#reminderid').val(id);
			$('#set-reminder-model').modal('show');
			$('#btn-reminder').addClass('save-resend-c-order').removeClass('save-resend-v-order');
		});

		$('body').on('click', '.save-resend-c-order', function () {
			var tmp_success = htmlsuccess;
    		var tmp_error = htmlerror;
			var day = $('#day').val();
			var id = $('#reminderid').val();

			if(day !== ''){
				$.ajax({
		            type: "POST",
		            url: 'ajax.php',
		            data: {'id':id, 'day': day, 'action':'resendSaleorder'},
		            dataType: "json",
		            beforeSend: function() {
		            	$('#btn-reminder').html('<i class="fa fa-spin fa-refresh"></i>');
		            	$('#btn-reminder').prop('disabled', true);
				    },
		            success: function (data) {
		            	$('#btn-reminder').prop('disabled', false);
		            	$('#btn-reminder').html('Resend');
		              	if(data.status == true){
                            showSuccessToast('Reminder added success.');
		              		//tmp_success = tmp_success.replace("##MSG##", 'Reminder added success.');
                			//$('#reminder-errormsg').html(tmp_success);
                			$('#set-reminder-model').modal('hide');
                			$('#TR-C-'+id).fadeOut('slow');
		              	}else{
                            showDangerToast('Reminder added fail!');
		              		// tmp_error = tmp_error.replace("##MSG##", 'Reminder added fail!');
                			// $('#reminder-errormsg').html(tmp_error);
                			return false;
		              	}
		            },
		            error: function () {
                            showDangerToast('Reminder added fail!');
		            	// tmp_error = tmp_error.replace("##MSG##", 'Reminder added fail!');
            			// $('#reminder-errormsg').html(tmp_error);
            			$('#btn-reminder').prop('disabled', false);
		            	$('#btn-reminder').html('Resend');
            			return false;
		            }
		        });
			}else{
                showDangerToast('day is required!');
				// tmp_error = tmp_error.replace("##MSG##", 'day is required!');
                // $('#reminder-errormsg').html(tmp_error);
                return false;
			}
		});
    /*---------CUSTOMER ORDER NOTIFICATION END-----------------*/
});