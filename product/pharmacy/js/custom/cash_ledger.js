$( document ).ready(function() {
    $("#from, #to").change(function(){
	    var from = $('#from').val();
	    var to = $('#to').val();

	    if(from != '' && to != ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'from':from, 'to':to, 'action':'cashRunningBalance'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                $('#running_balance').show();
                var running_balance = (typeof data.result.running_balance !== 'undefined' && data.result.running_balance !== '' && !isNaN(data.result.running_balance)) ? data.result.running_balance : 0;
                running_balance = (running_balance >= 0) ? Math.abs(running_balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Dr' : Math.abs(running_balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Cr';
                $('#running_balance').html('Running Balance : '+running_balance);
              }else{
                $('#running_balance').html('Running Balance : 0');
                $('#running_balance').hide();
              }
              
            },
            error: function () {
                $('#running_balance').html('Running Balance : 0');
                $('#running_balance').hide();
            }
          });
        }else{
          $('#running_balance').html('Running Balance : 0');
          $('#running_balance').hide();
        }
	});
});