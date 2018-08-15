$( document ).ready(function() {

	// ON CHABGE CITY TO GET ALL CUSTOMER RELATED TO CITY
    $("#city_id").change(function(){
	    var city_id = $(this).val();

	    if(city_id != ''){
	    	$.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'city_id':city_id, 'action':'getAllCustomerByCity'},
              dataType: "json",
              success: function (data) {console.log(data);
              	if(data.status == true){
              		$('#customer_id').children('option:not(:first)').remove();
              		$.each(data.result, function (i, item) {
					    $('#customer_id').append($('<option>', { 
					        value: item.id,
					        text : item.name 
					    }));
					});
              	}else{
              		$('#customer_id').children('option:not(:first)').remove();
              	}
              },
              error: function () {
              	$('#customer_id').children('option:not(:first)').remove();
              }
          	});
	    }else{
	    	$('#customer_id').children('option:not(:first)').remove();
	    }
	});

	// ON CHABGE CITY TO GET ALL CUSTOMER RELATED TO CITY
    $("#customer_id").change(function(){
	    var customer_id = $(this).val();
	    if(customer_id != ''){
	    	$.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'customer_id':customer_id, 'action':'getCustomerAddressById'},
              dataType: "json",
              success: function (data) {
              	if(data.status == true){
              		$('#c_addr_1').val((typeof data.result.addressline1 !== 'undefined') ? data.result.addressline1 : '');
			    	$('#c_addr_2').val((typeof data.result.addressline2 !== 'undefined') ? data.result.addressline2 : '');
			    	$('#c_addr_3').val((typeof data.result.addressline3 !== 'undefined') ? data.result.addressline3 : '');
              	}else{
              		blankAddress();
              	}
              },
              error: function () {
              	blankAddress();
              }
          	});
	    }else{
	    	blankAddress();
	    }
	});

    //THIS FUNCTION IS USED TO BLANK ADDRLINE 1 2 AND 3
	function blankAddress(){
		$('#c_addr_1').val(null);
    	$('#c_addr_2').val(null);
    	$('#c_addr_3').val(null);
	}

	// ON CHANGE BILL TYPE TO CHANGE INVOICE NUMBER
	$(".bill_type").change(function(){
		var bill_type = $("input[name='bill_type']:checked").val();
		$.ajax({
	      type: "POST",
	      url: 'ajax.php',
	      data: {'bill_type':bill_type, 'action':'getInvoiceNo'},
	      dataType: "json",
	      success: function (data) {
	      	$('#invoice_no').val(data.result);
	      },
	      error: function () {
	      	$('#invoice_no').val(null);
	      }
	  	});
	});


	// save customer to database

	$("#add-customer-form").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serializeArray();
        var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addcustomer', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addcustomer').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addcustomer').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#add_customer_model').modal('toggle');
                $('#add-customer-form')[0].reset();

                $('#add-customer-form').find('#country').val('').trigger('change');
                $('#add-customer-form').find('#state').val('').trigger('change');
                $('#add-customer-form').find('#city').val('').trigger('change');
              }else{
                htmlerror =  htmlerror.replace("##MSG##", data.message);
                $('#addcustomer-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addcustomer').html('Save');
              $('#btn-addcustomer').prop('disabled', false);
            },
            error: function () {
              htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              $('#addcustomer-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addcustomer').html('Save');
              $('#btn-addcustomer').prop('disabled', false);
            }
        });

    });

    // ADD MORE ITEM
    $('body').on('click', '.btn-add-more-item', function () {
    	var trlength = $('#item-tbody tr').length+1;
    	var html = $('#hiddenItemHtml').html();
    	html = html.replace('<table>','');
    	html = html.replace('</table>','');
    	html = html.replace('<tbody>','');
    	html = html.replace('</tbody>','');
    	html = html.replace('##SRNO##',trlength);
    	$('#item-tbody').append(html);
    });

    // ADD MORE ITEM
    $('body').on('click', '.btn-remove-item', function () {
    	$(this).closest ('tr').remove ();
    });
});