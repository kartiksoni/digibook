$( document ).ready(function() {

    $("#ladger_list, #from, #to").change(function(){

      
      var to = $('#to').val();
      var from = $('#from').val();
      var id = $('#ladger_list').val(); 
         

      if(id !== ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'id':id,'from':from, 'to':to, 'action':'allLedgerCalculation'},
            dataType: "json",
            success: function (data) {
       
              if(data.status == true){
           
                $('#running_balance').show();
                var running_balance = (typeof data.result.running_balance !== 'undefined' && data.result.running_balance !== '' && !isNaN(data.result.running_balance)) ? data.result.running_balance : 0;
                running_balance = (running_balance >= 0) ? Math.abs(running_balance).toLocaleString('en-IN', { style: 'decimal', maximumFractionDigits : 2, minimumFractionDigits : 2 })+' Dr' : Math.abs(running_balance).toLocaleString('en-IN', { style: 'decimal', maximumFractionDigits : 2, minimumFractionDigits : 2 })+' Cr';
                $('#running_balance').html('Running Balance : '+running_balance);
              }else{
                // alert("false");
                $('#running_balance').html('Running Balance : 0');
                $('#running_balance').hide();
              }
              
            },
            error: function () {
              // alert("error");
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