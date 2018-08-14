$( document ).ready(function() {
    $(".btn-applycr").click(function() {
	  var return_id = $(this).attr('data-id');
	  $('#apply-creditnote-model').modal('show');
	  $('#purchase_return_id').val(return_id);
	  getCreditNoteNo();
	});

	function getCreditNoteNo(){
		$.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'getCreditNoteNo'},
            dataType: "json",
            success: function (data) {
              $('#cr_no').val(data.result);
            },
            error: function () {
              
            }
        });
	}

	$(".credittype").change(function() {
		var val = $('.credittype:checked').val();

		if(val == 'Billing'){
			$('.cr_no_lable').html('Invoice No');
			$('.cr_date_lable').html('Invoice Date');
			$('.cr_amount_lable').html('Invoice Amount');
		}else{
			$('.cr_no_lable').html('Credit No');
			$('.cr_date_lable').html('Date');
			$('.cr_amount_lable').html('Amount');
		}

	});

	// SAVE CREDIT NOTE IN TO DATABASE

	$("#add-creditnote-form").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serialize();
        var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addCreditNote', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addcreditnote').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addcreditnote').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#apply-creditnote-model').modal('toggle');
                $('#add-creditnote-form')[0].reset();
                $('#tr-'+dataarr.purchase_return_id).find('.status').html('<div class="badge badge-outline-danger">Close</div>');
                
              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#addcreditnote-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addcreditnote').html('Save');
              $('#btn-addcreditnote').prop('disabled', false);
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#addcreditnote-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addcreditnote').html('Save');
              $('#btn-addcreditnote').prop('disabled', false);
            }
        });

    });

});