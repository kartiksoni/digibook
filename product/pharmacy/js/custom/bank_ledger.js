$( document ).ready(function() {
    $("#bank, #from, #to").change(function(){
	    var val = $("#bank").val();
	    var from = $('#from').val();
	    var to = $('#to').val();

	    if(val !== ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'id':val, 'from':from, 'to':to, 'action':'bankrunningbalance'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                $('#running_balance').show();
                
                var running_balance = (typeof data.result.bank_running !== 'undefined' && data.result.bank_running !== '' && !isNaN(data.result.bank_running)) ? data.result.bank_running : 0;

                running_balance = (running_balance >= 0) ? Math.abs(running_balance).toLocaleString('en-IN', { style: 'decimal', maximumFractionDigits : 2, minimumFractionDigits : 2 })+' Dr' : Math.abs(running_balance).toLocaleString('en-IN', { style: 'decimal', maximumFractionDigits : 2, minimumFractionDigits : 2 })+' Cr';

                $('#running_balance').html('Running Balance : '+running_balance);
              }else{
                $('#running_balance').html('Running Balance : 0.00 Dr');
                $('#running_balance').hide();
              }
              
            },
            error: function () {
                $('#running_balance').html('Running Balance : 0.00 Dr');
                $('#running_balance').hide();
            }
          });
        }else{
          $('#running_balance').html('Running Balance : 0.00 Dr');
          $('#running_balance').hide();
        }
	});
});