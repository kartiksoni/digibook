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
			              		tmp_success = tmp_success.replace("##MSG##", 'Reminder added success.');
                    			$('#reminder-errormsg').html(tmp_success);
                    			$('#set-reminder-model').modal('hide');
                    			$('#TR-V-'+id).fadeOut('slow');
			              	}else{
			              		tmp_error = tmp_error.replace("##MSG##", 'Reminder added fail!');
                    			$('#reminder-errormsg').html(tmp_error);
                    			return false;
			              	}
			            },
			            error: function () {
			            	tmp_error = tmp_error.replace("##MSG##", 'Reminder added fail!');
                			$('#reminder-errormsg').html(tmp_error);
                			$('#btn-reminder').prop('disabled', false);
			            	$('#btn-reminder').html('Resend');
                			return false;
			            }
			        });
				}else{
					tmp_error = tmp_error.replace("##MSG##", 'day is required!');
                    $('#reminder-errormsg').html(tmp_error);
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
		              		tmp_success = tmp_success.replace("##MSG##", 'Reminder added success.');
                			$('#reminder-errormsg').html(tmp_success);
                			$('#set-reminder-model').modal('hide');
                			$('#TR-C-'+id).fadeOut('slow');
		              	}else{
		              		tmp_error = tmp_error.replace("##MSG##", 'Reminder added fail!');
                			$('#reminder-errormsg').html(tmp_error);
                			return false;
		              	}
		            },
		            error: function () {
		            	tmp_error = tmp_error.replace("##MSG##", 'Reminder added fail!');
            			$('#reminder-errormsg').html(tmp_error);
            			$('#btn-reminder').prop('disabled', false);
		            	$('#btn-reminder').html('Resend');
            			return false;
		            }
		        });
			}else{
				tmp_error = tmp_error.replace("##MSG##", 'day is required!');
                $('#reminder-errormsg').html(tmp_error);
                return false;
			}
		});
    /*---------CUSTOMER ORDER NOTIFICATION END-----------------*/
        
    var pharmacyusertype = $('#navbar-top').attr('data-pharmacyusertype');    
    /*------------IHIS PRESCRIPTION START--------------------*/
        if(pharmacyusertype == 'ihis' || pharmacyusertype == 'eclinic'){
            var url = (pharmacyusertype == 'ihis') ? 'ajax.php?action=getIhisPrescription&type=1' : 'ajax.php?action=getEclinicPrescription&type=1';
            var table_ihis_prescription = $('.tbl_ihis_prescription').DataTable( {
                "ajax": url,
                "columns": [
                    { "data": "no" },
                    { "data": "patient_opd_id" },
                    { "data": "doctor_name" },
                    { "data": "p_name" },
                    { "data": "email" },
                    { "data": "mobile_no" },
                    { "data": "type" },
                    { "data" : "action",
                        "render": function (data)
                        {
                            data.bill_type = (typeof data.bill_type !== 'undefined') ? data.bill_type : '';
                            data.group_id = (typeof data.group_id !== 'undefined') ? data.group_id : '';
                            data.type = (typeof data.type !== 'undefined') ? data.type : '';
                            data.doctor_name = (typeof data.doctor_name !== 'undefined') ? data.doctor_name : '';
                            data.patient_primary_id = (typeof data.patient_primary_id !== 'undefined') ? data.patient_primary_id : '';
                            data.doctor_id = (typeof data.doctor_id !== 'undefined') ? data.doctor_id : '';
                            data.doctor_mobile = (typeof data.doctor_mobile !== 'undefined') ? data.doctor_mobile : '';
                            data.ihis_firm_id = (typeof data.ihis_firm_id !== 'undefined') ? data.ihis_firm_id : '';
                            data.ihis_user_id = (typeof data.ihis_user_id !== 'undefined') ? data.ihis_user_id : '';
                            data.ihis_ipd_id = (typeof data.ihis_ipd_id !== 'undefined') ? data.ihis_ipd_id : '';
                            data.ihis_treatment_by = (typeof data.ihis_treatment_by !== 'undefined') ? data.ihis_treatment_by : '';
                            data.ihis_patient_id = (typeof data.ihis_patient_id !== 'undefined') ? data.ihis_patient_id : '';
                            data.ihis_followup_id = (typeof data.ihis_followup_id !== 'undefined') ? data.ihis_followup_id : '';
                            data.register_type = (typeof data.register_type !== 'undefined') ? data.register_type : '';
                            data.infertility_register_type = (typeof data.infertility_register_type !== 'undefined') ? data.infertility_register_type : '';
                            
                            
                            var btn_first = '<a class="btn  btn-behance p-2" title="View Prescription" href="view-prescription.php?billtype='+data.bill_type+'&id='+data.group_id+'&type='+data.type+'&doctor_name='+data.doctor_name+'" target="_blank"><i class="fa fa-eye mr-0"></i></a>';
                            if(typeof data.is_pharmacy_bill !== 'undefined' && data.is_pharmacy_bill == 1){
                                btn_first += ' <a href="javascript:void(0);" class="btn  btn-success p-2" title="Bill Already Generated">Bill Generated</a>';
                            }else{
                                btn_first += ' <a class="btn  btn-success p-2" title="Generate Bill" href="sales-tax-billing.php?billtype='+data.bill_type+'&patient='+data.patient_primary_id+'&group='+data.group_id+'&doctor='+data.doctor_id+'&doctormobile='+data.doctor_mobile+'&type='+data.type+'&ihis_firm_id='+data.ihis_firm_id+'&ihis_user_id='+data.ihis_user_id+'&ihis_ipd_id='+data.ihis_ipd_id+'&ihis_treatment_by='+data.ihis_treatment_by+'&ihis_patient_id='+data.ihis_patient_id+'&ihis_followup_id='+data.ihis_followup_id+'&register_type='+data.register_type+'&infertility_register_type='+data.infertility_register_type+'"><i class="fa fa-mail-forward mr-0"></i></a>';
                            }
                            return btn_first;
                        }
        
                    }
        
                ],
                //"order": [[1, 'asc']]
            });
            window.setInterval(function(){
              table_ihis_prescription.ajax.reload();
            }, 7000);
        }
    /*------------IHIS PRESCRIPTION END--------------------*/
    
    /*-------------GET IHIS PRESCRIPTION NOTIFICATION- START -------------------*/
    if(pharmacyusertype == 'ihis' || pharmacyusertype == 'eclinic'){
        window.setInterval(function(){
          addIhisNotification(pharmacyusertype);
        }, 7000);
    }
    function addIhisNotification(pharmacyusertype = null){
        var url = (pharmacyusertype == 'ihis') ? 'getIhisPrescriptionNotification' : 'getEclinicPrescriptionNotification';
        $.ajax({
            type: "POST",
            url: 'ajax_second.php',
            data: {'action': url},
            dataType: "json",
            success: function (data) {
              	if(data.status == true){
              		if(typeof data.result !== 'undefined' && data.result.length > 0){
              		    var notifyCounter = $('.count-indicator').find('.count').text();
              		    notifyCounter = (typeof notifyCounter !== 'undefined' && !isNaN(notifyCounter) && notifyCounter != '') ? parseFloat(notifyCounter) : 0;
              		    
              		    
              		    var html = '';
              		    $.each(data.result, function(key, val) {
              		        var classname = val.type+'_'+val.id;
              		        if($('#top-notification-box').find("."+classname).length <= 0){
                  		        html += '<a href="view-prescription.php?id='+val.group_id+'&type='+val.type+'&doctor_name='+val.doctor_name+'&prescriptionid='+val.id+'" class="dropdown-item preview-item '+classname+'"><div class="preview-item-content"><h6 class="preview-subject">'+val.type+' Prescription</h6><p class="text-muted">Patient Name: '+val.patient_name+' | Doctor Name: '+val.doctor_name+'</p></div></a><div class="dropdown-divider"></div>';
                               notifyCounter++;
              		        }
              		    });
              		    
              		    if(html != ''){
              		        $('#top-notification-box').prepend(html);
              		        if($('.count-indicator').find('.count').length){
              		            $('.count-indicator').find('.count').text(notifyCounter);
              		        }else{
              		            $('.count-indicator').find('.mdi-bell-outline').after('<span class="count bg-danger">'+notifyCounter+'</span>');
              		        }
              		        
              		        if($('.empty-notification').length){
              		            $('.empty-notification').remove();
              		        }
              		    }
              		}
              	}else{
              		
              	}
            },
            error: function () {
            	
            }
        });
    }
    /*-------------GET IHIS PRESCRIPTION NOTIFICATION- END -------------------*/
});