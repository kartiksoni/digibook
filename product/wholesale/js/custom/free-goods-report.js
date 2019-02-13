$( document ).ready(function() {
  
  
  $(".type").click(function(){
      var type = $(this).val();

      if(type == 'company_wise'){
        $("#company_name").show();
        $("#ledger_name").hide();
        $("#ledger_name2").hide();
        $("#ledger").val(null);
        $("#ledger").hide();
      }else if(type == 'all'){
        $("#company_name").hide();
        $("#ledger_name").show();
        $("#company").val('');
        //$("#ledger").val(null);
        $("#ledger").hide();
        //$('input[name="sub_type"]').prop('checked', false);
      }else if(type=='party_wise'){
        $("#ledger_name2").show();
        $("#company").val('');
      } else if(type == 'all1'){
          $("#ledger_name2").hide();
          $("#company").val('');
          $("#ledger").val(null);
          $("#ledger").hide();
      }
    });
});