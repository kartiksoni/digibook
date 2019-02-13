$( document ).ready(function() {
    $('body').on('change', '#gst_id', function () {
        var val = $(this).val();
        
        var igst = $(this).find(':selected').attr('data-igst');
        var sgst = $(this).find(':selected').attr('data-sgst');
        var cgst = $(this).find(':selected').attr('data-cgst');
        
        if(val !== '' && val > 3){
            $('.gstdiv').show();
            $('#igst').val(igst);
            $('#sgst').val(sgst);
            $('#cgst').val(cgst);
        }else{
            $('.gstdiv').hide();
            $('#igst').val(0);
            $('#sgst').val(0);
            $('#cgst').val(0);
        }
    });
    
    $("#add-company-form").on("submit", function(event){
		event.preventDefault();
	    var data = $(this).serialize();
	    // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
	    // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
	    var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
	    $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addcompany', 'data': dataarr},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addcompany').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addcompany').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                showSuccessToast(data.message);
               // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
               // $('#addcompany-errormsg').html(htmlsuccess);
                //$('#btn-addcompany').html('Save');
                //$('#btn-addcompany').prop('disabled', false);
                $('#add-company-form')[0].reset();
                // $('#company_code').val(dataarr.code);
                
                $('#company_code').append($('<option>', { 
                    value: data.result,
                    text : dataarr.code+' - '+dataarr.name
                }));
                $('#company_code').val(data.result).trigger('change');
                
                setTimeout( function(){
                	$('#btn-addcompany').html('Save');
                	$('#btn-addcompany').prop('disabled', false);
                	$('#addcompany-model').modal('hide');
                	$('#addcompany-errormsg').html(null);
  				}  , 1000 );
              }else{
                showDangerToast(data.message);
                // htmlerror =  htmlerror.replace("##MSG##", data.message);
                // $('#addcompany-errormsg').html(htmlerror);
                $('#btn-addcompany').html('Save');
                $('#btn-addcompany').prop('disabled', false);
              }
            },
            error: function () {
                showDangerToast('Somthing Want Wrong!');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#addcompany-errormsg').html(htmlerror);
              $('#btn-addcompany').html('Save');
              $('#btn-addcompany').prop('disabled', false);
            }
        });

	});
	
	$('#company_code').change(function(){
     var title = $(this).val();
      if(title == 'add_new_companycode'){
        $('#addcompany-model').modal('show');
        $('#company_code').val('').trigger('change');
      }
  });
});