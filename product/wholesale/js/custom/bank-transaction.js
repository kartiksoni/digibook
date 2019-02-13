$( document ).ready(function() {
  $('body').on('change', '.reverse_charge', function () {
		var value = $(this).closest('.detail-row').find( '.reverse_charge:checked' ).val();
		if(value == 1){
			$(this).closest('.detail-row').find('.gst-div').show();
		}else{
			$(this).closest('.detail-row').find('.gst-div').hide();
		}
	});

  $('body').on('change', '.payment_mode', function () {
    var $this = $(this);
    var mode = $(this).val();

    if(mode == 'cheque'){
      $($this).closest('.detail-row').find('.payment_mode_date_div').show();
      $($this).closest('.detail-row').find('.payment_mode_date_lable').text('Check Date');
      $($this).closest('.detail-row').find('.payment_mode_no_div').show();
      $($this).closest('.detail-row').find('.payment_mode_no_lable').text('Check No.');
      $($this).closest('.detail-row').find('.payment_mode_no').val(null);
      $($this).closest('.detail-row').find('.card_name_div').hide();
      $($this).closest('.detail-row').find('.other_reference_div').hide();
    }else if(mode == 'dd'){
      $($this).closest('.detail-row').find('.payment_mode_date_div').show();
      $($this).closest('.detail-row').find('.payment_mode_date_lable').text('DD Date');
      $($this).closest('.detail-row').find('.payment_mode_no_div').show();
      $($this).closest('.detail-row').find('.payment_mode_no_lable').text('DD No.');
      $($this).closest('.detail-row').find('.payment_mode_no').val(null);
      $($this).closest('.detail-row').find('.card_name_div').hide();
      $($this).closest('.detail-row').find('.other_reference_div').hide();
    }else if(mode == 'net_banking'){
      $($this).closest('.detail-row').find('.payment_mode_date_div').hide();
      $($this).closest('.detail-row').find('.card_name_div').hide();
      $($this).closest('.detail-row').find('.other_reference_div').hide();
      $($this).closest('.detail-row').find('.payment_mode_no_div').show();
      $($this).closest('.detail-row').find('.payment_mode_no_lable').text('UTR No.');
      $($this).closest('.detail-row').find('.payment_mode_no').val(null);
    }else if(mode == 'credit_debit_card'){
      $($this).closest('.detail-row').find('.payment_mode_date_div').hide();
      $($this).closest('.detail-row').find('.other_reference_div').hide();
      $($this).closest('.detail-row').find('.payment_mode_no_div').show();
      $($this).closest('.detail-row').find('.payment_mode_no_lable').text('Card No.');
      $($this).closest('.detail-row').find('.payment_mode_no').val(null);
      $($this).closest('.detail-row').find('.card_name_div').show();
      $($this).closest('.detail-row').find('.card_name').val(null);
    }else if(mode == 'other'){
      $($this).closest('.detail-row').find('.payment_mode_date_div').hide();
      $($this).closest('.detail-row').find('.payment_mode_no_div').hide();
      $($this).closest('.detail-row').find('.card_name_div').hide();
      $($this).closest('.detail-row').find('.other_reference_div').show();
      $($this).closest('.detail-row').find('.other_reference').val(null);
    }else{
      $($this).closest('.detail-row').find('.payment_mode_date_div').hide();
      $($this).closest('.detail-row').find('.payment_mode_no_div').hide();
      $($this).closest('.detail-row').find('.card_name_div').hide();
      $($this).closest('.detail-row').find('.other_reference_div').hide();

      $($this).closest('.detail-row').find('.payment_mode_no').val(null);
      $($this).closest('.detail-row').find('.card_name').val(null);
      $($this).closest('.detail-row').find('.other_reference').val(null);
    }
  });

	$('body').on('click', '.btn-add-more-item', function () {
		var length = $('.detail-row:visible').length;
		console.log('length => '+length);
		var html = $('#hidden-detail-row').html();
		html = html.replace(/##KEY##/g,length);

		$('#detail-body').append(html).find('.type, .perticular').select2();
	});

	$('body').on('click', '.btn-remove-item', function () {
		$(this).closest('.detail-row').remove();
	});

	$('body').on('change', '.type', function () {
		var $this = $(this);
		var group_id = $(this).val();
		if(group_id != ''){
			$.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'group_id':group_id, 'action':'getgroup'},
              dataType: "json",
              success: function (data) {
                  if(data.status == true){
                      $($this).closest('.detail-row').find('.perticular').children('option:not(:first)').remove();
                      $.each(data.result, function (i, item) {
                        $($this).closest('.detail-row').find('.perticular').append($('<option>', { 
                            value: item.id,
                            text : item.name 
                        }));
                    });
                  }else{
                      $($this).closest('.detail-row').find('.perticular').children('option:not(:first)').remove();
                  }
              },
              error: function () {
                  $('#ledger').children('option:not(:first)').remove();
              }
              });
		}else{
			$($this).closest('.detail-row').find('.perticular').children('option:not(:first)').remove();
		}
	});

	$('body').on('change', '.perticular', function () {
		var $this = $(this);
        var id = $(this).val();
        if(id !== ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'id':id, 'action':'perticularRunningBalance'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                $($this).closest('.detail-row').find( '.running_balance' ).show();
                var running_balance = (typeof data.result.running_balance !== 'undefined' && data.result.running_balance !== '' && !isNaN(data.result.running_balance)) ? data.result.running_balance : 0;
                running_balance = (running_balance >= 0) ? Math.abs(running_balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Dr' : Math.abs(running_balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Cr';
                $($this).closest('.detail-row').find( '.running_balance' ).html(running_balance);
              }else{
                $('#ledger_running_balance').html(0);
                $('#ledger_running_balance').hide();
              }
              
            },
            error: function () {
                $($this).closest('.detail-row').find( '.running_balance' ).html(0);
        		$($this).closest('.detail-row').find( '.running_balance' ).hide();
            }
          });
        }else{
        	$($this).closest('.detail-row').find( '.running_balance' ).html(0);
        	$($this).closest('.detail-row').find( '.running_balance' ).hide();
        }
    });

    $('body').on('change keyup past', '.credit', function () {
      var val = $(this).val();
      val  = (typeof val !== 'undefined' && !isNaN(val) && val != '') ? parseFloat(val) : 0;
      if(val > 0){
        $(this).closest('.detail-row').find('.debit').val(null);
      }
    });

    $('body').on('change keyup past', '.debit', function () {
      var val = $(this).val();
      val  = (typeof val !== 'undefined' && !isNaN(val) && val != '') ? parseFloat(val) : 0;
      if(val > 0){
        $(this).closest('.detail-row').find('.credit').val(null);
      }
    });
});