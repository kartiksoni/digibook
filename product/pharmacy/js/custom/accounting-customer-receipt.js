// author : Kartik Champaneriya
// date   : 17-08-2018
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
});
    
    
    
    
    