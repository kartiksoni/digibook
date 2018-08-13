$( document ).ready(function() {
    
	$( "#company_code" ).autocomplete({
      source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query, 'action': 'getCompanyCode'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                if(data.status == true){
                  $(".empty-message").empty();
                    result($.map(data.result, function (item) {
                      return {
                          label: item.code,
                          value: item.id,
                          name: item.name,
                      }
                  }));
                }else{
                    $(".empty-message").text("No results found");
                }
              }
          });
        },
        focus: function( query, result ) {
          $( "#company_code" ).val( result.item.label );
          return false;
        },
        select: function( query, result ) {
            return false;
        }
  });


  $("#add-company-form").on("submit", function(event){
		  event.preventDefault();
	    var data = $(this).serialize();
	    var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
	    var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
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
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#addcompany-errormsg').html(htmlsuccess);
                //$('#btn-addcompany').html('Save');
                //$('#btn-addcompany').prop('disabled', false);
                $('#add-company-form')[0].reset();
                $('#company_code').val(dataarr.code);
                setTimeout( function(){
                	$('#btn-addcompany').html('Save');
                	$('#btn-addcompany').prop('disabled', false);
                	$('#addcompany-model').modal('hide');
                	$('#addcompany-errormsg').html(null);
  				}  , 1000 );
              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#addcompany-errormsg').html(htmlerror);
                $('#btn-addcompany').html('Save');
                $('#btn-addcompany').prop('disabled', false);
              }
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#addcompany-errormsg').html(htmlerror);
              $('#btn-addcompany').html('Save');
              $('#btn-addcompany').prop('disabled', false);
            }
        });
	});

  $('body').on('change keyup focusout', '.inward_rate,.opening_qty', function() {
    var inward_rate = $('.inward_rate').val();
    var opening_qty = $('.opening_qty').val();
    if(inward_rate !== ''&& inward_rate !== NaN && inward_rate !== "undifined" && opening_qty !=='' && opening_qty !== NaN && opening_qty !== "undifined"){

      inward_rate = (typeof inward_rate !== "undifined" && inward_rate !== '' && inward_rate !== NaN) ? inward_rate : 0;
      opening_qty = (typeof opening_qty !== "undifined" && opening_qty !== '' && opening_qty !== NaN) ? opening_qty : 0;
      var total = (parseInt(inward_rate)*parseInt(opening_qty));

    }else{
      var total ="0";
    }
    //$('.opening_stock').val(parseFloat(total).toFixed(2));
    $('.opening_stock').val(parseFloat(total).toFixed(2));
  });





});