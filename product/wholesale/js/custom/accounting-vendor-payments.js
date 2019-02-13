$(document).ready(function(){
    $("#payment_mode").change(function() {
      var payment_mode = $("#payment_mode").val();
      if(payment_mode == "cash"){
        $(".div_cheque").hide();
        $(".div_dd").hide();
        $(".div_net_banking").hide();
        $(".div_credit_debit_card").hide();
        $(".div_other").hide();
      }
      if(payment_mode == "cheque"){
        $(".div_cheque").show();
        $(".div_dd").hide();
        $(".div_net_banking").hide();
        $(".div_credit_debit_card").hide();
        $(".div_other").hide();
      }
      if(payment_mode == "dd"){
        $(".div_dd").show();
        $(".div_cheque").hide();
        $(".div_net_banking").hide();
        $(".div_credit_debit_card").hide();
        $(".div_other").hide();
      }
      if(payment_mode == "net_banking"){
        $(".div_cheque").hide();
        $(".div_dd").hide();
        $(".div_net_banking").show();
        $(".div_credit_debit_card").hide();
        $(".div_other").hide();
      }
      if(payment_mode == "credit_debit_card"){
        $(".div_cheque").hide();
        $(".div_dd").hide();
        $(".div_net_banking").hide();
        $(".div_credit_debit_card").show();
        $(".div_other").hide();
      }
      if(payment_mode == "other"){
        $(".div_cheque").hide();
        $(".div_dd").hide();
        $(".div_net_banking").hide();
        $(".div_credit_debit_card").hide();
        $(".div_other").show();
      }

    });
    
    $("#vendor_id").change(function() {
        var vendor_id = $(this).val();
        
        if(vendor_id !== ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'id':vendor_id, 'action':'vendorRunningBalance'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                var running_balance = (typeof data.result.running_balance !== 'undefined' && data.result.running_balance !== '' && !isNaN(data.result.running_balance)) ? data.result.running_balance : 0;
                running_balance = (running_balance >= 0) ? Math.abs(running_balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Dr' : Math.abs(running_balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Cr';
                $('#running_balance').html(running_balance);
              }else{
                $('#running_balance').html(0);
              }
              
            },
            error: function () {
                $('#running_balance').html(0);
            }
          });
        }else{
          $('#running_balance').html(0);
        }
    });

    /*$('#cheque_no').parsley( 'addListener', {
      onFieldValidate: function ( elem ) {
  
          // if field is not visible, do not apply Parsley validation !
          if ( !$( elem ).is( ':visible' ) ) {
              return true;
          }
          return false;
      }
  }); */
});